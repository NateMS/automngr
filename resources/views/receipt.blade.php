<!DOCTYPE html>
<html lang="de">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>
        Quittung - {{ $contract->car->name }} - {{ $contract->contact->full_title }}
    </title>
    <style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
        }
        body {
            margin: 0;
        }
        table{
            font-size: x-small;
        }
        table tr td {
            padding: 1px 0;
            font-size: 12px;
        }
        .footnote {
            padding: 5px 0;
            margin: 25px 0 5px 0;
            font-size: 9px;
            border-top: 1px solid black;
            border-bottom: 1px solid black;
        }
        tfoot tr td{
            font-weight: bold;
            font-size: x-small;
        }
        .gray {
            background-color: lightgray
        }
</style>
</head>
<body>
    <table width="100%">
        <tr>
            <td align="left">
                <pre>
@if ($contract->isSellContract())
<b>{{ App\Models\Setting::get('company_name', 'Your SwissCar GmbH') }}</b>
{{ App\Models\Setting::get('street', 'Bernstrasse 27') }}
{{ App\Models\Setting::get('city', '8952 Schlieren') }}

Tel: {{ App\Models\Setting::get('phone', '079 680 34 44') }}
E-Mail: {{ App\Models\Setting::get('email', 'info@yourswisscar.com') }}

MwSt-Nr: {{ App\Models\Setting::get('vat_number', 'CHE-226.272.050') }}
@else
<b>{{ $contract->contact->full_title }}</b>
{{ $contract->contact->address }}
{{ $contract->contact->full_city }}

Tel: {{ $contract->contact->phone }}
E-Mail: {{ $contract->contact->email }}
@endif
                </pre>
            </td>
            @if ($contract->isSellContract())
                <td valign="top" alt="logo" width="100%" align="right"><x-logo /></td>
            @endif
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td><h1>
                Quittung
            </h1></td>
        </tr>
    </table>
    <table width="100%">
        @if ($contract->isSellContract())
            <tr>
                <td width="15%"><b>Verkäufer</b></td>
                <td width="35%">{{ App\Models\Setting::get('company_name', 'Your SwissCar GmbH') }}</td>
                <td width="15%"><b>Käufer</b></td>
                <td width="35%">{{ $contract->contact->full_title }}</td>
            </tr>
            <tr>
                <td>Strasse</td>
                <td>{{ App\Models\Setting::get('street', 'Bernstrasse 27') }}</td>
                <td>Strasse</td>
                <td>{{ $contract->contact->address }}</td>
            </tr>
            <tr>
                <td>PLZ / Ort</td>
                <td>{{ App\Models\Setting::get('city', '8952 Schlieren') }}</td>
                <td>PLZ / Ort</td>
                <td>{{ $contract->contact->full_city }}</td>
            </tr>
            <tr>
                <td>Telefon</td>
                <td>{{ App\Models\Setting::get('phone', '079 680 34 44') }}</td>
                <td>Telefon</td>
                <td>{{ $contract->contact->phone }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>E-Mail</td>
                <td>{{ $contract->contact->email }}</td>
            </tr>
        @else
            <tr>
                <td width="15%"><b>Verkäufer</b></td>
                <td width="35%">{{ $contract->contact->full_title }}</td>
                <td width="15%"><b>Käufer</b></td>
                <td width="35%">{{ App\Models\Setting::get('company_name', 'Your SwissCar GmbH') }}</td>
            </tr>
            <tr>
                <td>Strasse</td>
                <td>{{ $contract->contact->address }}</td>
                <td>Strasse</td>
                <td>{{ App\Models\Setting::get('street', 'Bernstrasse 27') }}</td>
            </tr>
            <tr>
                <td>PLZ / Ort</td>
                <td>{{ $contract->contact->full_city }}</td>
                <td>PLZ / Ort</td>
                <td>{{ App\Models\Setting::get('city', '8952 Schlieren') }}</td>
            </tr>
            <tr>
                <td>Telefon</td>
                <td>{{ $contract->contact->phone }}</td>
                <td>Telefon</td>
                <td>{{ App\Models\Setting::get('phone', '079 680 34 44') }}</td>
            </tr>
            @if ($contract->contact->email != '')
                <tr>
                    <td>E-Mail</td>
                    <td>{{ $contract->contact->email }}</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            @endif
        @endif
        <tr><td>&nbsp;</td></tr>
    </table>
    <table width="100%">
        <tr><td>Fahrzeug:</td></tr>
        <tr>
            <td><b>{{ $contract->car->name_with_year }}</b><br>Stammnummer: {{ $contract->car->stammnummer }}</td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr>
            <td>Hiermit wird die folgende Einzahlung für das oben genannte Fahrzeug bestätigt:</td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="20%">Datum</td>
            <td>{{ $payment->date }}</td>
        </tr>
        <tr>
            <td>Zahlungsart</td>
            <td>{{ $payment->type }}</td>
        </tr>
        <tr>
            <td>Betrag</td>
            <td><h4>{{ $payment->amount->format() }}</h4></td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>&nbsp;</td></tr>
    </p>
    <table width="100%">
        <tr>
            <td>Ort, Datum</td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr>
            <td>{{ App\Models\Setting::get('signature_city', 'Schlieren') }}, {{ $payment->date }}</td>
        </tr>
    </table>
</body>
</html>