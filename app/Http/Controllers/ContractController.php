<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Car;
use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Contact;
use App\Models\Contract;
use App\Enums\ContractType;
use App\Enums\InsuranceType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Redirect;

class ContractController extends Controller
{

    public function dashboard()
    {
        return Inertia::render('Dashboard', [
            'bought_this_year' => Contract::boughtThisYear()->count(),
            'sold_this_year' => Contract::soldThisYear()->count(),
            'my_cars' => Car::unsoldOnly()->count(),
            'buy_contracts' => Contract::buyContracts()
                                ->orderBy('date', 'desc')
                                ->limit(10)
                                ->get()
                                ->map(function ($contract) {
                                    return [
                                        'date' => $contract->date_formatted,
                                        'price' => $contract->price->format(),
                                        'car' => $contract->car->name_with_year,
                                        'contact' => $contract->contact->title,
                                        'link' => route('contracts.show', $contract),
                                    ];
                                }),
            'sell_contracts' => Contract::sellContracts()
                                ->orderBy('date', 'desc')
                                ->limit(10)
                                ->get()
                                ->map(function ($contract) {
                                    return [
                                        'date' => $contract->date_formatted,
                                        'price' => $contract->price->format(),
                                        'car' => $contract->car->name_with_year,
                                        'contact' => $contract->contact->title,
                                        'link' => route('contracts.show', $contract),
                                    ];
                                }),
        ]);
    }

    public function create(Request $request, string $type, Car $car, Contact $contact)
    {
        return Inertia::render('Contracts/Create', [
            'car' => $this->getCarFields($car),
            'contact' => $this->getContactFields($contact),
            'is_sell_contract' => ContractType::coerce($type) == ContractType::SellContract,
            'type' => $type,
            'car_first' => (bool)$request->query('carFirst'),
            'insurance_types' => InsuranceType::asSelectArray(),
        ]);
    }

    public function createFromCar(Request $request, string $type, Car $car)
    {
        return Inertia::render('Contracts/CreateFromCar', [
            'car' => $this->getCarFields($car),
            'is_sell_contract' => ContractType::coerce($type) == ContractType::SellContract,
            'type' => $type,
            'contacts' => Contact::all()->map(function ($contact) {
                return $this->getContactFields($contact);
            }),
        ]);
    }

    public function createFromContact(Request $request, string $type, Contact $contact)
    {
        $contractType = ContractType::coerce($type);
        $cars = $contractType->value == ContractType::SellContract ? Car::unsoldOnly() : Car::soldOnly();

        return Inertia::render('Contracts/CreateFromContact', [
            'contact' => $this->getContactFields($contact),
            'is_sell_contract' => $contractType == ContractType::SellContract,
            'type' => $type,
            'cars' => $cars->get()->map(function ($car) {
                return $this->getCarFields($car);
            }),
            'brands' => Brand::all()->map(function ($brand) {
                return [
                    'id' => $brand->id,
                    'name' => $brand->name,
                    'models' => $brand->carModels()->get()
                        ->map(function ($carModel) {
                            return [
                                'id' => $carModel->id,
                                'name' => $carModel->name,
                            ];
                        }),
                ];
            }),
        ]);
    }

    private function getCarFields(?Car $car) {
        if (!$car) {
            return null;
        }
        return [
            'id' => $car->id,
            'stammnummer' => $car->stammnummer,
            'vin' => $car->vin,
            'name' => $car->name,
            'colour' => $car->colour,
            'initial_date' => $car->initial_date_formatted,
        ];
    }

    private function getContactFields(?Contact $contact) {
        if (!$contact) {
            return null;
        }
        return [
            'id' => $contact->id,
            'title' => $contact->full_title,
            'name' => $contact->name,
            'firstname' => $contact->firstname,
            'lastname' => $contact->lastname,
            'phone' => $contact->phone,
            'address' => $contact->address,
            'zip' => $contact->zip,
            'city' => $contact->city,
            'country' => $contact->country,
            'company' => $contact->company,
            'email' => $contact->email,
        ];
    }

    public function store(Request $request)
    {
        $request->merge([
            'type' => (string)$request->get('type'),
            'insurance_type' => (string)$request->get('insurance_type'),
            'date' => Carbon::parse($request->get('date'))->format('Y-m-d'),
            'delivery_date' => Carbon::parse($request->get('delivery_date'))->format('Y-m-d'),
        ]);

        $contract = Contract::create(
            $request->validate([
                'type' => ['required', 'string', Rule::in(ContractType::getValues())],
                'date' => ['required', 'date'],
                'delivery_date' => ['required', 'date'],
                'price' => ['required', 'integer'],
                'car_id' => ['required', 'exists:App\Models\Car,id'],
                'contact_id' => ['required', 'exists:App\Models\Contact,id'],
                'insurance_type' => ['nullable', 'string', Rule::in(InsuranceType::getValues())],
                'notes' => ['nullable'],
            ])
        );

        session()->flash('flash.banner', 'Vertrag erstellt.');
        return Redirect::route('contracts.show', $contract);

    }

    public function edit(Contract $contract)
    {
        return Inertia::render('Contracts/Edit', [
            'contract' => [
                'id' => $contract->id,
                'date' => $contract->date,
                'delivery_date' => $contract->delivery_date,
                'date_formatted' => $contract->date_formatted,
                'is_sell_contract' => $contract->isSellContract(),
                'type' => $contract->type,
                'type_formatted' => $contract->type_formatted,
                'notes' => $contract->notes,
                'price' => (int)$contract->price->getAmount(),
                'insurance_type' => (string)$contract->insurance_type,
                'car' => [
                    'id' => $contract->car->id,
                    'name' => $contract->car->name,
                ],
            ],
            'insurance_types' => InsuranceType::asSelectArray(),
        ]);
    }

    public function update(Request $request, Contract $contract)
    {
        $request->merge([
            'insurance_type' => (string)$request->get('insurance_type'),
            'date' => Carbon::parse($request->get('date'))->format('Y-m-d'),
            'delivery_date' => Carbon::parse($request->get('delivery_date'))->format('Y-m-d'),
        ]);

        $contract->update(
            $request->validate([
                'date' => ['required', 'date'],
                'delivery_date' => ['required', 'date'],
                'price' => ['required', 'integer'],
                'insurance_type' => ['nullable', 'string', Rule::in(InsuranceType::getValues())],
                'notes' => ['nullable'],
            ])
        );

        session()->flash('flash.banner', 'Vertrag geändert.');
        return Redirect::route('contracts.show', $contract);
    
    }

    public function show(Contract $contract)
    {
       return Inertia::render('Contracts/Show', [
        'contract' => [
            'id' => $contract->id,
            'date' => $contract->date_formatted,
            'delivery_date' => $contract->delivery_date_formatted,
            'price' => $contract->price->format(),
            'type' => $contract->type,
            'type_formatted' => $contract->type_formatted,
            'notes' => $contract->notes,
            'paid' => $contract->paid->format(),
            'left_to_pay' => $contract->left_to_pay->format(),
            'left_to_pay_raw' => (int)$contract->left_to_pay->getAmount(),
            'is_sell_contract' => $contract->isSellContract(),
            'documents' => $contract->documents()->orderBy('created_at', 'asc')->get()
                ->map(function ($document) {
                    return [
                        'id' => $document->id,
                        'name' => $document->name,
                        'size' => $document->size,
                        'extension' => $document->extension,
                        'link' => $document->link,
                        'created_at' => $document->created_at,
                    ];
                }),
            'payments' => $contract->payments()->orderBy('date', 'asc')->get()
                ->map(function ($payment) {
                return [
                        'id' => $payment->id,
                        'date' => $payment->date,
                        'amount' => $payment->amount->format(),
                        'type' => $payment->type,
                        'delete_link' => $payment->delete_link,
                ];
            }),
            'insurance_type' => $contract->insurance_type ? InsuranceType::fromValue($contract->insurance_type)->key : null,
            'deleted_at' => $contract->deleted_at,
            'contact' => $contract->contact->only(['id', 'full_title', 'link']),
            'car' => $contract->car->only(['id', 'name_with_year', 'link']),
        ],
    ]);
    }

    public function print(Contract $contract)
    {
        $contxt = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed'=> TRUE
            ]
        ]);
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('contract', compact('contract'));
        $pdf->getDomPDF()->setHttpContext($contxt);
        return $pdf->stream($contract->date . '_' . $contract->type_formatted . '.pdf');
    }

    public function destroy(Contract $contract)
    {
        $contract->delete();
        session()->flash('flash.banner', 'Vertrag gelöscht.');
        return Redirect::route('contracts.show', $contract);
    }

    public function restore(Contract $contract)
    {
        $contract->restore();
        session()->flash('flash.banner', 'Vertrag wiederhergestellt.');
        return Redirect::route('contracts.show', $contract);
    }
}
