<!DOCTYPE html>
<html>

<head>
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

<body>
    <p></p>
    <p>Wykaz godzin pracy za okres
        @if ($from == '1970-01-01')
            {{ $from = '' }}
        @else
            od:
            {{ \Carbon\Carbon::parse($from)->format('d-m-Y') }}

        @endif

        do: {{ \Carbon\Carbon::parse($to)->format('d-m-Y') }}
    </p>
    <table id="customers">
        <tr>
            <td>Imię i nazwisko</td>
            <td>Dzień pracy</td>

            <td>Ilość godzin</td>
            <td>Kontrahent</td>
        </tr>
        @php
            $total = 0;
        @endphp
        @foreach ($hours as $worker)
            @php
                $total += $worker->hours;
            @endphp
            <tr>
                <td>{{ $worker->workers_name }} {{ $worker->workers_surname }}</td>
                <td>{{ $worker->work_day }}</td>

                <td>{{ $worker->hours }}</td>
                <td>{{ $worker->contrahents_name }}</td>
            </tr>
        @endforeach

        <tr>
            <td></td>
            <td> Razem: </td>
            <td> <b> {{ $total }}</b>
            </td>
            <td>
            </td>
        </tr>
    </table>
</body>

</html>
