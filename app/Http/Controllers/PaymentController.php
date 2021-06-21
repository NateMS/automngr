<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Payment;
use App\Models\Contract;
use App\Enums\PaymentType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;

class PaymentController extends Controller
{
    public function create()
    {
        return Inertia::render('Payments/Create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'type' => (string)$request->get('type'),
        ]);

        $request->validate([
            'date' => ['required', 'date_format:"d.m.Y"'],
            'amount' => ['required', 'integer'],
            'type' => ['required', 'string', Rule::in(PaymentType::getValues())],
            'contract_id' => ['required', 'exists:App\Models\Contract,id'],
        ]);

        $request->merge([
            'date' => Carbon::parse($request->get('date'))->format('Y-m-d'),
        ]);

        $payment = Payment::create($request->all());

        session()->flash('flash.banner', 'Einzahlung gespeichert.');
        return Redirect::route('contracts.show', $payment->contract);
    }

    public function destroy(Request $request, Contract $contract)
    {
        if (Payment::destroy((int)$request->get('id'))) {
            session()->flash('flash.banner', 'Einzahlung gelöscht.');
        } else {
            session()->flash('flash.banner', 'Fehler beim Löschen, Einzahlung nicht gefunden.');
        }
        
        return Redirect::back();
    }
}
