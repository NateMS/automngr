<?php

namespace App\Http\Controllers;

use App\Enums\PaymentType;
use App\Models\Contract;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function create()
    {
        return Inertia::render('Payments/Create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'type' => (string) $request->get('type'),
        ]);

        $request->validate([
            'date' => ['required', 'date_format:"d.m.Y"'],
            'amount' => ['required', 'integer', 'min:1'],
            'type' => ['required', 'string', Rule::in(PaymentType::getValues())],
            'contract_id' => ['required', 'exists:App\Models\Contract,id'],
        ]);

        $request->merge([
            'date' => Carbon::parse($request->get('date'))->format('Y-m-d'),
        ]);

        $payment = Payment::create($request->only([
            'date', 'amount', 'type', 'contract_id',
        ]));

        session()->flash('flash.banner', 'Einzahlung gespeichert.');

        return Redirect::route('contracts.show', $payment->contract);
    }

    public function print(Contract $contract, Payment $payment)
    {
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('receipt', compact('contract', 'payment'));

        return $pdf->stream($payment->date.'_quittung.pdf');
    }

    public function destroy(Contract $contract, Payment $payment)
    {
        if ($payment->contract_id !== $contract->id) {
            abort(404);
        }

        $payment->delete();
        session()->flash('flash.banner', 'Einzahlung gelöscht.');

        return Redirect::back();
    }
}
