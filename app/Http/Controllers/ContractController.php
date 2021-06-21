<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Car;
use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Contact;
use App\Models\Payment;
use App\Models\Contract;
use App\Enums\PaymentType;
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

    public function create(Request $request)
    {
        $type = (string)($request->get('type') ?? '1');
        $car = Car::find($request->get('car'));
        $contact = Contact::find($request->get('contact'));

        return Inertia::render('Contracts/Create', [
            'car' => $this->getCarFields($car),
            'contact' => $this->getContactFields($contact),
            'type' => $type,
            'insurance_types' => InsuranceType::asSelectArray(),
            'contacts' => Contact::all()->map(function ($contact) {
                return $this->getContactFields($contact);
            }),
            'cars' => Car::all()->map(function ($car) {
                return $this->getCarFields($car);
            }),
            'brands' => Brand::all()->map(function ($brand) {
                return [
                    'id' => $brand->id,
                    'name' => $brand->name,
                    'models' => $brand->carModels()->get(['id', 'name']),
                ];
            }),
        ]);
    }

    private function getCarFields(?Car $car) {
        if (!$car) {
            return [
                'name' => null,
                'label' => null,
                'id' => null,
                'stammnummer' => null,
                'vin' => null,
                'name' => null,
                'colour' => null,
                'initial_date' => null,
            ];
        }
        return [
            'name' => $car->name,
            'label' => $car->name . ' (' . $car->stammnummer . ')',
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
            return [
                'id' => null,
                'title' => null,
                'name' => null,
                'firstname' => null,
                'lastname' => null,
                'phone' => null,
                'address' => null,
                'zip' => null,
                'city' => null,
                'country' => null,
                'company' => null,
                'email' => null,
            ];
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
            'payment_type' => (string)$request->get('payment_type'),
            'insurance_type' => (string)$request->get('insurance_type'),
        ]);

        $request->validate([
            'type' => ['required', 'string', Rule::in(ContractType::getValues())],
            'date' => ['required', 'date_format:"d.m.Y"'],
            'delivery_date' => ['required', 'date_format:"d.m.Y"'],
            'price' => ['required', 'integer'],
            'car_id' => ['required', 'exists:App\Models\Car,id'],
            'contact_id' => ['required', 'exists:App\Models\Contact,id'],
            'insurance_type' => ['nullable', 'string', Rule::in(InsuranceType::getValues())],
            'notes' => ['nullable'],
        ]);

        $request->merge([
            'date' => Carbon::parse($request->get('date'))->format('Y-m-d'),
            'delivery_date' => Carbon::parse($request->get('delivery_date'))->format('Y-m-d'),
        ]);

        $contract = Contract::create($request->all());

        $request->merge([
            'type' => (string)$request->get('payment_type'),
            'contract_id' => $contract->id,
        ]);

        if ($request->get('amount') && $request->get('type')) {
            Payment::create(
                $request->validate([
                    'date' => ['required', 'date'],
                    'amount' => ['required', 'integer'],
                    'type' => ['required', 'string', Rule::in(PaymentType::getValues())],
                    'contract_id' => ['required', 'exists:App\Models\Contract,id'],
                ])
            );
        }

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
        ]);

        $request->validate([
            'date' => ['required', 'date_format:"d.m.Y"'],
            'delivery_date' => ['required', 'date_format:"d.m.Y"'],
            'price' => ['required', 'integer'],
            'insurance_type' => ['nullable', 'string', Rule::in(InsuranceType::getValues())],
            'notes' => ['nullable'],
        ]);

        $request->merge([
            'date' => Carbon::parse($request->get('date'))->format('Y-m-d'),
            'delivery_date' => Carbon::parse($request->get('delivery_date'))->format('Y-m-d'),
        ]);

        $contract->update($request->all());

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
