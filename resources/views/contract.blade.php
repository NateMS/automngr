<!DOCTYPE html>
<html lang="de">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

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
<b>Your SwissCar GmbH</b>
Bernstrasse 27
8952 Schlieren

Tel: 079 680 34 44
E-Mail: info@yourswisscar.com

MwSt-Nr: CHE-226.272.050
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
                Auto - {{ $contract->type_formatted }}
                @if ($contract->isSellContract())
                / Rechnung / Quittung
                @endif
            </h1></td>
        </tr>
    </table>
    <table width="100%">
        @if ($contract->isBuyContract())
            <tr>
                <td width="15%"><b>Verkäufer</b></td>
                <td width="35%">{{ $contract->contact->full_title }}</td>
                <td width="15%"><b>Käufer</b></td>
                <td width="35%">Your SwissCar GmbH</td>
            </tr>
            <tr>
                <td>Strasse</td>
                <td>{{ $contract->contact->address }}</td>
                <td>Strasse</td>
                <td>Bernstrasse 27</td>
            </tr>
            <tr>
                <td>PLZ / Ort</td>
                <td>{{ $contract->contact->full_city }}</td>
                <td>PLZ / Ort</td>
                <td>8952 Schlieren</td>
            </tr>
            <tr>
                <td>Telefon</td>
                <td>{{ $contract->contact->phone }}</td>
                <td>Telefon</td>
                <td>079 680 34 44</td>
            </tr>
        @else
            <tr>
                <td width="15%"><b>Verkäufer</b></td>
                <td width="35%">Your SwissCar GmbH</td>
                <td width="15%"><b>Käufer</b></td>
                <td width="35%">{{ $contract->contact->full_title }}</td>
            </tr>
            <tr>
                <td>Strasse</td>
                <td>Bernstrasse 27</td>
                <td>Strasse</td>
                <td>{{ $contract->contact->address }}</td>
            </tr>
            <tr>
                <td>PLZ / Ort</td>
                <td>8952 Schlieren</td>
                <td>PLZ / Ort</td>
                <td>{{ $contract->contact->full_city }}</td>
            </tr>
            <tr>
                <td>Telefon</td>
                <td>079 680 34 44</td>
                <td>Telefon</td>
                <td>{{ $contract->contact->phone }}</td>
            </tr>
        @endif
        <tr><td>&nbsp;</td></tr>
    </table>
    <h4>Fahrzeug</h4>
    <table width="100%">
        <tr>
            <td width="20%">Marke & Modell</td>
            <td width="30%">{{ $contract->car->name }}</td>
            <td width="20%">Farbe</td>
            <td width="30%">{{ $contract->car->colour }}</td>
        </tr>
        <tr>
            <td>Chassisnummer</td>
            <td>{{ $contract->car->vin }}</td>
            <td>Stammnummer</td>
            <td>{{ $contract->car->stammnummer }}</td>
        </tr>
        <tr>
            <td>Inverkehrssetzung</td>
            <td>{{ $contract->car->initial_date_formatted }}</td>
            <td>Letzte Prüfung</td>
            <td>{{ $contract->car->last_check_date_formatted }}</td>
        </tr>
        <tr>
            <td>Kilometerstand</td>
            <td>{{ $contract->car->kilometers_formatted }}</td>
            <td>Garantie</td>
            <td>{{ $contract->insurance_type_formatted }}</td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr>
            <td>Kaufpreis</td>
            <td><b>{{ $contract->price }}</b></td>
        </tr>
            <tr>
                <td valign="top">Anzahlung</td>
                @if ($contract->paid_in_cash->getAmount() && $contract->paid_in_transaction->getAmount())
                    <td>{{ $contract->paid_in_cash }} in bar<br>{{ $contract->paid_in_transaction }} via Überweisung</td>
                @elseif ($contract->paid_in_cash->getAmount())
                    <td>{{ $contract->paid_in_cash }} in bar</td>
                @elseif ($contract->paid_in_transaction->getAmount())
                    <td>{{ $contract->paid_in_transaction }} via Überweisung</td>
                @else
                    <td>{{ $contract->paid }}</td>
                @endif
            </tr>
        <tr>
            <td>Restbetrag</td>
            <td>{{ $contract->left_to_pay }}</td>
        </tr>
        <tr>
            <td>Bankverbindung IBAN</td>
            <td>CH69 0900 0000 1549 3981 7</td>
        </tr>
        <tr>
            <td>Lieferdatum</td>
            <td>{{ $contract->delivery_date_formatted }}</td>
        </tr>
        <tr>
            <td valign="top"><b>Bemerkung</b></td>
            <td>{{ $contract->notes }}</td>
        </tr>
    </table>
    <pre class="footnote" width="100%">
@if ($contract->isSellContract())
Serviceheft + alle Dokumente sind vorhanden. Mit Kratze Lackschaden.
Mit der Anzahlung/Teilzahlung des Kaufpreises durch den Käufer anerkennt
er den gesamten Kaufpreis gekauft wie gesehen und probiert.
Ohne Gewährleistung 
Garantie für Occasionen entfallen die gesetzlichen Gewährleistungsansprüche, 
eine gänzliche oder teilweise Rückerstattung des Kaufpreises
(Wandelung und Minderung) sind ausgeschlossen, ebenso der Ersatz eines aus der
mangelhaften Lieferung irgendwie entstandenen Schadens.
Gerichtsstand: Für die Beurteilung aller aus dieser Rechnung entstehenden
Streitigkeiten gilt das Domizil der Verkaufsfirma.
Eigentumsvorbehalt: Das Fahrzeug bleibt bis zur vollständigen
Bezahlung des Kaufpreises unser Eigentum
@else
Der Verkäufer erklärt hiermit ausdrücklich, dass er alleinger und legitimer Eigentümer des
Fahrzeug ist.
Ebenso bestätigt er ,dass es sich nicht um ein Leasing-, oder Mietauto handelt, sowie weder eine Restfinanzierung 
noch ein Eigentumsvorbehalt und/oder eine Pfändung / Beschlagnahmung auf das Fahrzeug besteht.
Der Verkäufer garantiert auch, dass das Fahrzeug unfallfrei (gemäss Bemerkung) ist und der Kilometerstand der Realität entspricht.
Mündliche Vereinbarungen sind ungültig.
@endif
</pre>
    <table width="100%">
        <tr>
            <td width="20%">Ort, Datum</td>
            <td width="30%">Schlieren, {{ $contract->date_formatted }}</td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr>
            <td>Verkäufer:</td>
            <td></td>
            <td>Käufer:</td>
        </tr>
    </table>
    <br><br>
    @if (count($contract->payments))
        <h4>Quittung</h4>
        <table width="100%" style="padding-top: 5px; border-top: 1px solid black;">
            @foreach($contract->payments as $payment)
                <tr>
                    <td width="20%">Am {{ $payment->date }}</td>
                    <td width="30%">den Betrag von {{ $payment->amount->format() }}</td>
                    <td>{{ $payment->type_text }}</td>
                </tr>
            @endforeach
        </table>
    @endif
</body>
</html>