<!DOCTYPE html>
<html lang="en">

<head>

    <head>
        <title>Rozliczenia</title>
        <meta http-equiv="Content-Type" content="charset=utf-8" />

        <style>
            * {
                font-family: "DejaVu Sans Mono", monospace;
            }

            #customers {
                border-collapse: collapse;
                width: 100%;
            }

            #customers td,
            #customers th {
                border: 1px solid #ddd;
                padding: 8px;
            }

            #customers tr:nth-child(even) {
                background-color: #f2f2f2;
            }

            #customers tr:hover {
                background-color: #ddd;
            }

            #customers th {
                padding-top: 12px;
                padding-bottom: 12px;
                text-align: center;
                background-color: #04AA6D;
                color: white;
            }

        </style>
    </head>

</head>

<body>

    <p>Rozliczenie PDF</p>
    @if ($from == '1970-01-01')
        <p>Rozliczenia za okres do: {{ \Carbon\Carbon::parse($to)->format('d-m-Y') }}</p>
    @else
        <p>Rozliczenia za okres od {{ \Carbon\Carbon::parse($from)->format('d-m-Y') }} do:
            {{ \Carbon\Carbon::parse($to)->format('d-m-Y') }}</p>

    @endif

    <table id="customers">

        <tr>
            <td>Pracownik</td>
            <td>Dzień</td>
            <td>Godziny</td>
            <td>Stawka</td>
            <td>Kontrahent</td>
            <td>Stawka</td>
            <td>Zysk</td>

        </tr>
        @php
            $sum = 0;
            $sum2 = 0;
            $sum3 = 0;
            $sum4 = 0;

        @endphp

        @foreach ($workers as $worker)

            <tr>
                <td>{{ $worker->name }} {{ $worker->surname }}</td>
                <td>{{ $worker->work_day }}</td>
                <td>{{ $worker->hours }}</td>
                <td>{{ $worker->workers_price_hour }}</td>
                <td>{{ $worker->contrahent_name }}</td>
                <td>{{ $worker->contrahents_salary_invoice }}</td>
                @php

                    $wh = $worker->hours;
                    $sc = $worker->contrahents_salary_invoice;
                    $totalC = $wh * $sc;

                    $sw = $worker->workers_price_hour;
                    $totalW = $wh * $sw;
                    $total_together = $totalC - $totalW;
                    $sum += $worker->contrahents_salary_invoice * $worker->hours;
                    $sum2 += $worker->workers_price_hour * $worker->hours;
                    $sum3 = $sum - $sum2;

                    //              sum += all_items[i].salary_invoice * all_items[i].hours;
                    // sum2 +=
                    //     all_items[i].workers_price_hour *
                    //     all_items[i].hours;

                    // sum3 += sum - sum2;

                @endphp

                <td>
                    {{ $total_together }}

                </td>
            </tr>


        @endforeach
        @php
            $sum4 = $sum - $sum2;

        @endphp

        <tr>
            <td>Podsumowanie</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Dochód:</td>
            <td> {{ $sum4 }}</td>
        </tr>
    </table>


</body>

</html>
