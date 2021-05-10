<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Contacts/Index', [
            'filters' => Request::all('search', 'trashed'),
            'contacts' => Contact::all()
                ->orderByName()
                ->filter(Request::only('search', 'trashed'))
                ->paginate()
                ->withQueryString()
                ->through(function ($contact) {
                    return [
                        'id' => $contact->id,
                        'name' => $contact->name,
                        'phone' => $contact->phone,
                        'zipcode' => $contact->city,
                        'city' => $contact->city,
                        'deleted_at' => $contact->deleted_at,
                    ];
                }),
        ]);
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
        Contact::create(
            Request::validate([
                'firstname' => ['required', 'max:75'],
                'lastname' => ['required', 'max:75'],
                'email' => ['nullable', 'max:75', 'email'],
                'phone' => ['max:75'],
                'address' => ['nullable', 'max:150'],
                'zipcode' => ['nullable', 'max:6'],
                'city' => ['nullable', 'max:75'],
                'country' => ['nullable', 'max:2'],
                'company' => ['nullable', 'max:75'],
            ])
        );

        return Redirect::route('contacts')->with('success', 'Kontakt erstellt.');
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
                'firstname' => $contact->first_name,
                'lastname' => $contact->last_name,
                'email' => $contact->email,
                'phone' => $contact->phone,
                'address' => $contact->address,
                'zipcode' => $contact->postal_code,
                'city' => $contact->city,
                'country' => $contact->country,
                'deleted_at' => $contact->deleted_at,
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
            Request::validate([
                'firstname' => ['required', 'max:75'],
                'lastname' => ['required', 'max:75'],
                'email' => ['nullable', 'max:75', 'email'],
                'phone' => ['max:75'],
                'address' => ['nullable', 'max:150'],
                'zipcode' => ['nullable', 'max:6'],
                'city' => ['nullable', 'max:75'],
                'country' => ['nullable', 'max:2'],
                'company' => ['nullable', 'max:75'],
            ])
        );

        return Redirect::route('contacts')->with('success', 'Kontakt geändert.');
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
