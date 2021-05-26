<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Contact;
use App\Models\Car;
use App\Enums\InsuranceType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
                    'link' => route('contacts.edit', $contact),
                    'deleted_at' => $contact->deleted_at,
                ]),
        ]);
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Contacts/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $contact = Contact::create(
            $request->validate([
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
            ])
        );

        return Redirect::route('contacts.edit', $contact);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
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
                'bought_cars' => $contact->buyContracts()
                    ->with('car')
                    ->paginate(10)
                    ->through(fn ($contract) => [
                        'date' => $contract->date,
                        'price' => $contract->price,
                        'name' => $contract->car->name,
                        'link' => route('cars.edit', $contract->car),
                        'insurance_type' => InsuranceType::fromValue((int)$contract->insurance_type)->key,
                    ]),
                'sold_cars' => $contact->sellContracts()
                    ->with('car')
                    ->paginate(10)
                    ->through(fn ($contract) => [
                        'date' => $contract->date,
                        'price' => $contract->price,
                        'name' => $contract->car->name,
                        'link' => route('cars.edit', $contract->car),
                    ]),
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        $contact->update(
            $request->validate([
                'firstname' => ['max:75'],
                'lastname' => ['max:75'],
                'email' => ['nullable', 'max:75', 'email'],
                'phone' => ['max:75'],
                'address' => ['nullable', 'max:150'],
                'zip' => ['nullable', 'max:6'],
                'city' => ['nullable', 'max:75'],
                'country' => ['nullable', 'max:2'],
                'company' => ['nullable', 'max:75'],
                'notes' => ['nullable'],
            ])
        );

        return Redirect::back()->with('success', 'Kontakt geändert.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return Redirect::back()->with('success', 'Kontakt gelöscht.');
    }

    public function restore(Contact $contact)
    {
        $contact->restore();

        return Redirect::back()->with('success', 'Kontakt wiederhergestellt.');
    }
}
