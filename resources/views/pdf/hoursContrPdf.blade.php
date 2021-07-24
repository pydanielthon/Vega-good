<!DOCTYPE html>
<html>

<head>

    <title></title>
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
    <p>Wykaz godzin pracy za okres
        @if ($from = '1970-01-01')
            {{ $from = '' }}
        @else
            od:
            {{ $from }}

        @endif

        do: {{ $to }}
    </p>
    <table id="customers">

        <tr>
            <td>Imię</td>
            <td>Nazwisko</td>

            <td>Dzień pracy</td>
            <td>Ilość godzin</td>
        </tr>
        @php
            $total = 0;
        @endphp
        @foreach ($contrahents as $hour)
            @php
                $total += $hour->hours;
            @endphp
            <tr>
                <td>{{ $hour->name }}</td>
                <td>{{ Str::limit($hour->surname, 3) }}</td>

                <td>{{ $hour->work_day }}</td>
                <td>{{ $hour->hours }}</td>

            </tr>
        @endforeach
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>
                Razem: {{ $total }}
            </td>
        </tr>
    </table>
</body>

</html>
