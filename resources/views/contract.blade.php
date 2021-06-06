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
            margin: 50px;
        }
        table{
            font-size: x-small;
        }
        table tr td {
            padding: 2px 0;
            font-size: 13px;
        }
        .footnote tr td {
            padding: 0;
            font-size: 11px;
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
                <h3>Your SwissCar GmbH</h3>
                <pre>
Bernstrasse 27
8952 Schlieren

Tel: 079 680 34 44
E-Mail: info@yourswisscar.com

MwSt-Nr: CHE-226.272.050
                </pre>
            </td>
            <td valign="top" width="100%" align="right"><img src="{{asset('images/logo.png')}}" alt="" width="250"/></td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td><h1>Autokaufvertag</h1></td>
        </tr>
    </table>
    <table width="100%">
        <tr><td>&nbsp;</td></tr>
        <tr>
            <td width="20%"><b>Verkäufer</b></td>
            <td width="30%">Your SwissCar GmbH</td>
            <td width="20%"><b>Käufer</b></td>
            <td width="30%">{{ $contract->contact->full_title }}</td>
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
        <tr><td>&nbsp;</td></tr>
    </table>
    <table width="100%">
        <tr>
            <td><h3>Fahrzeug</h3></td>
        </tr>
        <tr>
            <td width="20%">Marke & Modell</td>
            <td colspan="3" width="100%">{{ $contract->car->name }}</td>
        </tr>
        <tr>
            <td>Chassisnummer</td>
            <td colspan="3" width="100%">{{ $contract->car->vin }}</td>
        </tr>
        <tr>
            <td>Farbe</td>
            <td width="30%">{{ $contract->car->colour }}</td>
            <td width="20%">Inverkehrssetzung</td>
            <td width="30%">{{ $contract->car->initial_date_formatted }}</td>
        </tr>
        <tr>
            <td>Stammnummer</td>
            <td>{{ $contract->car->stammnummer }}</td>
            <td>Letzte Prüfung</td>
            <td>{{ $contract->car->last_check_date_formatted }}</td>
        </tr>
        <tr>
            <td>Kilometerstand</td>
            <td>{{ $contract->car->kilometers }}</td>
            <td>Garantie</td>
            <td>{{ $contract->insurance_type_formatted }}</td>
        </tr>
        <tr><td>&nbsp;</td></tr>

        <tr>
            <td>Verkaufspreis</td>
            <td><b>{{ $contract->price }}</b></td>
        </tr>
        <tr>
            <td>Anzahlung</td>
            <td></td>
        </tr>
        <tr>
            <td>Restbetrag</td>
            <td></td>
        </tr>
        <tr>
            <td>Bankverbindung IBAN</td>
            <td>CH69 0900 0000 1549 3981 7</td>
        </tr>
        <tr>
            <td>Lieferdatum</td>
            <td>{{ $contract->date_formatted }}</td>
        </tr>
        <tr>
            <td><b>Bemerkung</b></td>
            <td></td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>&nbsp;</td></tr>
    </table>
    <table class="footnote" width="100%" style="border-top: 1px solid black;">
        <tr>
            <td>Serviceheft + alle Dokumente sind vorhanden. Mit Kratze Lackschaden.</td>
        </tr>
        <tr>
            <td>Mit der Anzahlung/Teilzahlung des Kaufpreises durch den Käufer anerkennt</td>
        </tr>
        <tr>
            <td>er den gesamten Kaufpreis gekauft wie gesehen und probiert.</td>
        </tr>
        <tr>
            <td>Ohne Gewährleistung </td>
        </tr>
        <tr>
            <td>Garantie für Occasionen entfallen die gesetzlichen Gewährleistungsansprüche, </td>
        </tr>
        <tr>
            <td>eine gänzliche oder teilweise Rückerstattung des Kaufpreises</td>
        </tr>
        <tr>
            <td>(Wandelung und Minderung) sind ausgeschlossen, ebenso der Ersatz eines aus der</td>
        </tr>
        <tr>
            <td>mangelhaften Lieferung irgendwie entstandenen Schadens.</td>
        </tr>
        <tr>
            <td>Gerichtsstand: Für die Beurteilung aller aus dieser Rechnung entstehenden</td>
        </tr>
        <tr>
            <td>Streitigkeiten gilt das Domizil der Verkaufsfirma.</td>
        </tr>
        <tr>
            <td>Eigentumsvorbehalt: Das Fahrzeug bleibt bis zur vollständigen</td>
        </tr>
        <tr>
            <td>Bezahlung des Kaufpreises unser Eigentum.</td>
        </tr>
    </table>
    <table width="100%" style="border-top: 1px solid black;">
        <tr><td>&nbsp;</td></tr>
        <tr>
            <td width="20%">Ort, Datum</td>
            <td width="15%">Schlieren, </td>
            <td width="15%"></td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr>
            <td>Verkäufer:</td>
            <td colspan="2"></td>
            <td>Käufer:</td>
        </tr>
    </table>
    <br><br><br><br>
    <table width="100%" style="border-top: 1px solid black;">
        <tr>
            <td><b>Quittung</b></td>
            <td colspan="2">Den Betrag von</td>
            <td></td>
            <td>in bar erhalten</td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr>
            <td>Datum: </td>
            <td>{{ $contract->date_formatted }}</td>
        </tr>
</body>
</html>