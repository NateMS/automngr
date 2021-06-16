<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Inertia\Inertia;
use App\Exports\Export;
use App\Models\Contact;
use App\Models\Contract;
use App\Enums\InsuranceType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Redirect;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        return $this->renderContactsList($request, Contact::query(), 'Contacts/Index');
    }

    public function sellers(Request $request)
    {
        return $this->renderContactsList($request, Contact::has('buyContracts'), 'Contacts/Sellers');
    }

    public function buyers(Request $request)
    {
        return $this->renderContactsList($request, Contact::has('sellContracts'), 'Contacts/Buyers');
    }

    private function renderContactsList(Request $request, $contacts, string $renderPage) {
        $direction = $this->getDirection($request);
        $sortBy = $this->getSortBy($request);
        $contacts = $this->getWithCustomSort($contacts, $sortBy, $direction);

        return Inertia::render($renderPage, [
            'filters' => $request->all('search', 'trashed'),
            'sort' => [
                'by' => $sortBy,
                'direction' => $direction,
            ],
            'contacts' => $contacts->filter($request->only('search', 'trashed'))
                ->paginate(50)
                ->withQueryString()
                ->through(fn ($contact) => [
                    'id' => $contact->id,
                    'name' => $contact->name,
                    'company' => $contact->company,
                    'phone' => $contact->phone,
                    'email' => $contact->email,
                    'address' => $contact->address,
                    'fullCity' => $contact->fullCity,
                    'link' => route('contacts.show', $contact),
                    'deleted_at' => $contact->deleted_at,
                ]),
        ]);
    }

    public function print(Request $request)
    {
       return $this->printList($request, Contact::query(), date('Y-m-d') . '-Alle-Kontakte.xlsx');
    }

    public function buyersPrint(Request $request)
    {
       return $this->printList($request, Contact::has('buyContracts'), date('Y-m-d') . '-Verkäufer.xlsx');
    }

    public function sellersPrint(Request $request)
    {
       return $this->printList($request, Contact::has('sellContracts'), date('Y-m-d') . '-Käufer.xlsx');
    }

    private function printList(Request $request, $contacts, $title)
    {
        $headings = [
            'Nachname',
            'Vorname',
            'Firma',
            'Adresse',
            'PLZ',
            'Ort',
            'Land',
            'Email',
            'Telefon',
            'Bemerkungen',
        ];

        $direction = $this->getDirection($request);
        $sortBy = $this->getSortBy($request);
        $contacts = $this->getWithCustomSort($contacts, $sortBy, $direction)
                    ->filter($request->only('search', 'trashed'))
                    ->get()
                    ->map(function ($contact) {
                        return [
                            'lastname' => $contact->lastname,
                            'firstname' => $contact->firstname,
                            'company' => $contact->company,
                            'address' => $contact->address,
                            'zip' => $contact->zip,
                            'city' => $contact->city,
                            'country' => $contact->country,
                            'email' => $contact->email,
                            'phone' => $contact->phone,
                            'notes' => $contact->notes,
                        ];
                    });

        return Excel::download(new Export($contacts, $headings), $title);
    }

    private function getWithCustomSort($contacts, string $sortBy, string $direction)
    {
        switch($sortBy) {
            case 'company':
                return $contacts->orderBy('company', $direction);
            case 'fullCity':
                return $contacts->orderBy('city', $direction);
            case 'email':
                return $contacts->orderBy('email', $direction);
            case 'address':
                return $contacts->orderBy('address', $direction);
            default:
                return $contacts->orderByName($direction);
        }
    }

    private function getSortBy(Request $request)
    {
        if ($request->has('sortby')) {
            return $request->get('sortby');
        }

        return 'name';
    }

    public function create()
    {
        return Inertia::render('Contacts/Create');
    }

    public function store(Request $request)
    {
        $contact = Contact::create(
            $request->validate($this->getValidationRules())
        );

        session()->flash('flash.banner', 'Kontakt erstellt.');
        return Redirect::route('contacts.show', $contact);
    }

    public function storeForContract(Request $request)
    {
        $contact = Contact::create(
            $request->validate($this->getValidationRules())
        );

        return response()->json([
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
        ]);
    }

    public function edit(Contact $contact)
    {
        return Inertia::render('Contacts/Edit', [
            'contact' => [
                'id' => $contact->id,
                'firstname' => $contact->firstname,
                'lastname' => $contact->lastname,
                'company' => $contact->company,
                'title' => $contact->title,
                'email' => $contact->email,
                'notes' => $contact->notes,
                'phone' => $contact->phone,
                'address' => $contact->address,
                'zip' => $contact->zip,
                'city' => $contact->city,
                'country' => $contact->country,
                'deleted_at' => $contact->deleted_at,
            ]
        ]);
    }

    public function show(Contact $contact)
    {
        return Inertia::render('Contacts/Show', [
            'contact' => [
                'id' => $contact->id,
                'firstname' => $contact->firstname,
                'lastname' => $contact->lastname,
                'company' => $contact->company,
                'title' => $contact->title,
                'name' => $contact->name,
                'email' => $contact->email,
                'notes' => $contact->notes,
                'phone' => $contact->phone,
                'address' => $contact->address,
                'zip' => $contact->zip,
                'city' => $contact->city,
                'country' => $contact->country,
                'deleted_at' => $contact->deleted_at,
                'buy_contracts' => $contact->buyContracts()
                    ->with('car')
                    ->paginate(50)
                    ->through(fn ($contract) => $this->getContractFields($contract)),
                'sell_contracts' => $contact->sellContracts()
                    ->with('car')
                    ->paginate(50)
                    ->through(fn ($contract) => $this->getContractFields($contract)),
            ]
        ]);
    }

    private function getContractFields(?Contract $contract) {
        if (!$contract) {
            return null;
        }
        $car = $contract->car;
        return [
            'id' => $contract->id,
            'date' => $contract->date_formatted,
            'delivery_date' => $contract->delivery_date_formatted,
            'price' => $contract->price->format(),
            'paid' => $contract->paid->format(),
            'left_to_pay' => $contract->left_to_pay->format(),
            'type' => $contract->type,
            'notes' => $contract->notes,
            'is_sell_contract' => $contract->isSellContract(),
            'insurance_type' => $contract->insurance_type ? InsuranceType::fromValue($contract->insurance_type)->key : null,
            'car' => [
                 'id' => $car->id,
                    'stammnummer' => $car->stammnummer,
                    'vin' => $car->vin,
                    'name' => $car->name,
                    'initial_date' => $car->initial_date_formatted,
                    'colour' => $car->colour,
                    'last_check_date' => $car->last_check_date_formatted,
                    'kilometers' => $car->kilometers_formatted,
                    'known_damage' => $car->known_damage,
                    'notes' => $car->notes,
                    'link' => route('cars.show', $car),
            ],
            'link' => route('contracts.show', $contract),
        ];
    }

    public function update(Request $request, Contact $contact)
    {
        $contact->update(
            $request->validate($this->getValidationRules())
        );

        session()->flash('flash.banner', 'Kontakt geändert.');
        return Redirect::route('contacts.show', $contact);
    }

    private function getValidationRules()
    {
        return [
            'firstname' => ['max:75'],
            'lastname' => ['max:75'],
            'email' => ['nullable', 'max:75', 'email'],
            'phone' => ['required', 'max:75'],
            'address' => ['nullable', 'max:150'],
            'zip' => ['nullable', 'max:6'],
            'city' => ['nullable', 'max:75'],
            'country' => ['nullable', 'max:2'],
            'company' => ['nullable', 'max:75'],
            'notes' => ['nullable'],
        ];
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        session()->flash('flash.banner', 'Kontakt gelöscht.');
        return Redirect::back();
    }

    public function restore(Contact $contact)
    {
        $contact->restore();
        session()->flash('flash.banner', 'Kontakt wiederhergestellt.');
        return Redirect::back();
    }
}
