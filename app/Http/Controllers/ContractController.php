<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Contact;
use App\Models\Contract;
use App\Enums\ContractType;
use Barryvdh\DomPDF\Facade as PDF;
use App\Enums\InsuranceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return [];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function buyContracts(Request $request)
    {
        return [];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sellContracts(Request $request)
    {
        return [];
    }       

    public function create(Request $request, int $type, Car $car, Contact $contact)
    {
        return Inertia::render('Contracts/Create', [
            'car' => $this->getCarFields($car),
            'contact' => $this->getContactFields($contact),
            'type' => ContractType::coerce($type)->key,
            'car_first' => $request->query('carFirst'),
        ]);
    }

    public function createFromCar(Request $request, int $type, Car $car)
    {
        return Inertia::render('Contracts/CreateFromCar', [
            'car' => $this->getCarFields($car),
            'type' => ContractType::coerce($type)->key,
            'contacts' => Contact::all()->map(function ($contact) {
                return $this->getContactFields($contact);
            }),
        ]);
    }

    public function createFromContact(Request $request, int $type, Contact $contact)
    {
        $contractType = ContractType::coerce($type);
        $cars = $contractType->value == ContractType::SellContract ? Car::unsoldOnly() : Car::soldOnly();

        return Inertia::render('Contracts/CreateFromContact', [
            'contact' => $this->getContactFields($contact),
            'type' => $contractType->key,
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
            'last_check_date' => $car->last_check_date_formatted,
            'kilometers' => $car->kilometers,
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
        $contract = Contract::create(
            $request->validate([
                'type' => ['required'],
                'date' => ['required', 'date'],
                'price' => ['required', 'integer'],
                'car_id' => ['required', 'exists:App\Models\Car,id'],
                'contact_id' => ['required', 'exists:App\Models\Contact,id'],
                'insurance_type' => ['nullable', 'max:75'],
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
                'is_sell_contract' => $contract->isSellContract(),
                'price' => $contract->price->getAmount(),
                'insurance_type' => (string)$contract->insurance_type,
                'car' => [
                    'id' => $contract->car->id,
                    'name' => $contract->car->name,
                ],
            ],
            'insurance_types' => InsuranceType::asArray(),
        ]);
    }

    public function update(Request $request, Contract $contract)
    {
        $contract->update(
            $request->validate([
                'date' => ['required', 'date'],
                'price' => ['required', 'integer'],
                'insurance_type' => ['nullable', 'max:75'],
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
            'price' => $contract->price->format(),
            'type' => $contract->type,
            'is_sell_contract' => $contract->isSellContract(),
            'insurance_type' => $contract->insurance_type ? InsuranceType::fromValue((int)$contract->insurance_type)->key : null,
            'deleted_at' => $contract->deleted_at,
            'contact' => [
                'id' => $contract->contact->id,
                'name' => $contract->contact->name,
                'firstname' => $contract->contact->firstname,
                'lastname' => $contract->contact->lastname,
                'phone' => $contract->contact->phone,
                'address' => $contract->contact->address,
                'zip' => $contract->contact->zip,
                'city' => $contract->contact->city,
                'country' => $contract->contact->country,
                'company' => $contract->contact->company,
                'email' => $contract->contact->email,
                'link' => route('contacts.show', $contract->contact),
            ],
            'car' => [
                'id' => $contract->car->id,
                'stammnummer' => $contract->car->stammnummer,
                'vin' => $contract->car->vin,
                'car_model' => $contract->car->carModel->only('id', 'name'),
                'brand' => $contract->car->brand,
                'name' => $contract->car->name,
                'initial_date' => $contract->car->initial_date_formatted,
                'colour' => $contract->car->colour,
                'last_check_date' => $contract->car->last_check_date_formatted,
                'kilometers' => $contract->car->kilometers,
                'known_damage' => $contract->car->known_damage,
                'notes' => $contract->car->notes,
                'deleted_at' => $contract->car->deleted_at,
                'link' => route('cars.show', $contract->car),
            ],
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
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('contract', compact('contract'));//->setPaper('a4', 'portrait');
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
