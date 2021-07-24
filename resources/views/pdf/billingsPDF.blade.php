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
    <div>
        <p>Bilans PDF</p>
        @if ($from == '1970-01-01')
            <p>Rozliczenia za okres do: {{ \Carbon\Carbon::parse($to)->format('d-m-Y') }}</p>
        @else
            <p>Rozliczenia za okres od {{ \Carbon\Carbon::parse($from)->format('d-m-Y') }} do:
                {{ \Carbon\Carbon::parse($to)->format('d-m-Y') }}</p>

        @endif
        <p>Dodatkowe filtry wyszukiwania:</p>
        <p>W opisie: {{ $notes }}</p>
        <p>Kategoria: {{ $category }}</p>
    </div>
    <table id="customers">
        @if (!($category == 'zaliczki podsumowanie'))
            <tr>
                <td>Data</td>
                <td>Kategoria</td>
                <td>Kwota</td>
            </tr>


            @foreach ($requested as $req)

                <tr>
                    <td>{{ $req->date }}</td>
                    <td> {{ $req->category_name }}</td>
                    <td> {{ $req->price }}</td>
                </tr>

            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td>Suma: {{ $sum }}</td>
            </tr>
        @else

            <tr>
                <td>Data</td>
                <td>Kogo dotyczy</td>
                <td>Kwota</td>
                <td>Suma</td>
            </tr>
            <tr>
                <td><strong>Pracownicy</strong></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            {{ $prevCat = '4' }}
            {{ $workersSum = 0 }}

            @foreach ($requested as $req)
                @if ($prevCat != $req->category_id)
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{ $workersSum }}</td>
                    </tr>
                    <tr>
                        <td><strong>Kontrahenci</strong></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    {{ $sum = $workersSum }}
                @endif
                {{ $workersSum += $req->price }}
                <tr>
                    <td>{{ $req->date }}</td>
                    @if ($req->category_id == '4')
                        <td> {{ $req->workers_name }}</td>

                    @else
                        <td> {{ $req->contrahents_name }}</td>

                    @endif
                    <td> {{ $req->price }}</td>
                    <td></td>
                </tr>
                {{ $prevCat = $req->category_id }}
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ $workersSum - $sum }}</td>
            </tr>
            <tr>
                <td><strong>Łącznie</strong></td>
                <td></td>
                <td></td>
                <td>{{ $workersSum - $sum - $sum }}</td>
            </tr>

        @endif

    </table>


</body>

</html>
