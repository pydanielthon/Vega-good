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
    <p>Ralization date
        @if ($date_from = '1970-01-01')
            {{ $date_from = '' }}
        @else
            from:
            {{ \Carbon\Carbon::parse($date_from)->format('d-m-Y') }}

        @endif

        to: {{ \Carbon\Carbon::parse($date_to)->format('d-m-Y') }}
    </p>
    <table id="customers">

        <tr>
            <td>Name</td>
            <td>Surname</td>

            <td>Realization date</td>
            <td>Amount of hours</td>
        </tr>
        @foreach ($hours as $hour)

            <tr>
                <td>{{ $hour->name }}</td>
                <td>{{ Str::limit($hour->surname, 3) }}</td>

                <td>{{ \Carbon\Carbon::parse($hour->work_day)->format('d-m-Y') }}</td>
                <td>{{ $hour->hours }}</td>
            </tr>
        @endforeach
        <tr>
            <td></td>
            <td></td>
            <td><b>Total</b></td>
            <td><b>{{ $total_hours }}</b>
            </td>
        </tr>
    </table>
</body>

</html>
