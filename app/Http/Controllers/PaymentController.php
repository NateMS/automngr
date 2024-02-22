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

    public function print(Contract $contract, Payment $payment)
    {
        $contxt = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed'=> true,
            ],
        ]);
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('receipt', compact('contract', 'payment'));
        $pdf->getDomPDF()->setHttpContext($contxt);

        return $pdf->stream($payment->date.'_quittung.pdf');
    }

    public function destroy(Request $request, Contract $contract)
    {
        if (Payment::destroy((int) $request->get('id'))) {
            session()->flash('flash.banner', 'Einzahlung gelöscht.');
        } else {
            session()->flash('flash.banner', 'Fehler beim Löschen, Einzahlung nicht gefunden.');
        }

        return Redirect::back();
    }
}
