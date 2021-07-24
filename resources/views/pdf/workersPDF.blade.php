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
    <p>Rozliczenie godzin pracy za okres od:{{ \Carbon\Carbon::parse($date_from)->format('d-m-Y') }} do:
        {{ \Carbon\Carbon::parse($date_to)->format('d-m-Y') }}</p>
    <table id="customers">
        <tr>
            <td>Ilość Dni</td>
            <td></td>

            <td></td>
        </tr>


        <tr>
            <td>Zaliczki</td>
            <td>Z dnia</td>
            <td> w kwocie</td>
        </tr>
        @foreach ($billings as $bill)
            <tr>
                <td></td>
                <td>{{ \Carbon\Carbon::parse($bill->date)->format('d-m-Y') }}</td>
                <td> {{ $bill->price }}</td>
            </tr>
        @endforeach

     </tr>

            <tr>
            <td>Prąd</td>
            <td></td>

            <td>{{ $electricity }}</td>
        </tr>
        <tr>
            <td>Ilośc Godzin</td>
            <td></td>

            <td>{{ $total_hours }}</td>
        </tr>
        
    
        <tr>
            <td></td>
            <td></td>

            <td>* {{ $price_hour }} (stawka)</td>
   
        <tr>
            <td></td>

            <td></td>
            <td>{{ $total_salary }} </td>

        </tr>

        suma - suma zaliczek + koszta dodatkowe
        <tr>
            <td></td>

            <td></td>
            <td>{{ $total_salary }} - {{ $deposit }} - {{$electricity}} = {{ $total_salary_minus_deposit }}</td>

        </tr>
        <tr>
            <td>Koszta dodatkowe</td>

            <td></td>

            <td></td>
        </tr>
        .foreach

    </table>
</body>

</html>
