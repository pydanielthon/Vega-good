@extends('layouts.app')

@section('content')
    <div class="container-fluid workers__show">
        @if (!empty($workerd))
            <div class="section-title">
                <p class="title-text">{{ $workerd->name }} {{ $workerd->surname }}</p>
            @can('Usun pracownika')
                <form action="{{ route('workers.destroy', $workerd->id) }}" method="POST">

                    @csrf
                    <input type="hidden" name="_method" value="DELETE">

                    @method('DELETE')
                    <button type="submit" class="nav-link submit-form button-border add-action-link action-delete"><img
                            src='{{ URL::to('/images/iconmonstr-plus-6.svg') }}' alt="Usuń pracownika">
                        <p>Usuń
                            pracownika</p>
                    </button>
                </form>
@endcan
            </div>
            <div class="table-container">
                <div class="table-title-container">
                    <p class="table-title">Dane pracownika</p>
                    @can('Edytuj pracownika')
                        <a class="edit-action" href="{{ route('workers.edit', $workerd->id) }}">Edytuj</a>
                    @endcan
                </div>
                <!-- <p>{{ $workerd->name }} {{ $workerd->surname }}</p> -->
                <div class="d-flex align-items-center show-info"><span>Stawka: </span>
                    <p class='col-md-7'>{{ $workerd->price_hour }}</p>
                </div>
                  <div class="d-flex align-items-center show-info"><span>Stawka za prąd: </span>
                    <p class='col-md-7'>{{ $workerd->electric_price }}</p>
                </div>
                <div class="d-flex align-items-center show-info"> <span>Adres: </span>
                    <p class='col-md-7'>{{ $workerd->address }}</p>
                </div>
                @if ($workerd->notes)
                    <div class="d-flex  align-items-center show-info"> <span>Notatki: </span>
                        <p class='col-md-7'>{{ $workerd->notes }}</p>
                    </div>
                @endif

                    @can('Rozlicz pracownika')


                <div class="table-title-container">
                    <p class="table-title">Rozliczanie godzin</p>
                </div>
                <p class="unpaid_hours-container">Pracownik ma <span id="unpaid_hours">{{ $all_hours ?? '' }}
                    </span> nie rozliczonych godzin. <span id="last_billing">
              <span id="last_billing">
                            Data ostatniego rozliczenia<b>
                                {{ date('d-m-Y', strtotime($lastBill)) == '01-01-1970' ? 'brak' : date('d-m-Y', strtotime($lastBill)) }}</b>
                        </span>
                    </span>

                </p>

                <div class="billing-container-print">
                    <p>Rozliczenie godzin do dnia</p>
                    <form method="POST" action="{{ route('workers.getPaid', $workerd->id) }}">
                        @csrf
                        <input type="date" max="{{ date('Y-m-d') }}" class="form-control" name="date"
                            id="inputDateToPaid" value="{{ $today }}">
                        @if ($all_hours == 0)
                            <button class="btn btn-primary add-action-link-green disabled" disabled type="submit"
                                id="paid_worker" name="button-form" data="billing" value="billing"><i
                                    class="fas fa-sync-alt"></i>
                                <p>Rozlicz</p>
                            </button>
                        @else
                            <button class="btn add-action-link-green btn-primary" type="submit" id="paid_worker"
                                name="button-form" data="billing" value="billing"><i class="fas fa-sync-alt"></i>
                                <p>Rozlicz</p>
                            </button>
                        @endif
                        @if ($all_hours == 0)

                        @else

                            <!-- <a class="btn btn-primary" href="{{ URL::to('workers/generate-pdf', ['id' => $workerd->id]) }}">Drukuj
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    Rozliczenie</a> -->
                        @endif

                    </form>
                </div>
                @endcan
                <div class="table-title-container">
                    <p class="table-title">Lista rozliczeń</p>
                </div>
                <div class="billings-list">


                    Pokaż listę rozliczeń od: <input type="date" max="{{ date('Y-m-d') }}" class="form-control"
                        name="date" id="inputPaidDateFrom">
                    do: <input type="date" max="{{ date('Y-m-d') }}" value="{{ $today }}" class="form-control"
                        name="date" id="inputPaidDateTo">
                    <button class="btn btn-primary add-action-link-green" id="paidButton"><img
                            src='{{ URL::to('/images/iconmonstr-magnifier-6.svg') }}' alt="pokaż listę">
                        <p>Pokaż</p>
                    </button>

                </div>
                <div id="result" class='table-responsive'></div>
                <div class="table-title-container">
                    <p class="table-title">Lista godzin</p>
                </div>

                <div class="hours-list">


                    Godziny od: <input type="date" max="{{ date('Y-m-d') }}" class="form-control" name="date"
                        id="inputHourDateFrom" placeholder="">
                    do: <input type="date" max="{{ date('Y-m-d') }}" class="form-control" name="date"
                        id="inputHourDateTo" value="{{ $today }}">
                    <button class="btn btn-primary add-action-link-green" id="hourButton"><img
                            src='{{ URL::to('/images/iconmonstr-magnifier-6.svg') }}' alt="pokaż listę">
                        <p>Pokaż</p>
                    </button>

                </div>
                <div id="result2" class='table-responsive'></div>
        @endif

    </div>
    </div>
    </div>
    <script>
        function formatDate(dateString) {
            var allDate = dateString.split(' ');
            console.log(allDate)
            var thisDate = allDate[0].split('-');
            var newDate = [thisDate[2], thisDate[1], thisDate[0]].join("-");



            return newDate;
        }
        let buttonGetPaid = document.querySelector("#hourButton");

        console.log(buttonGetPaid)

        buttonGetPaid.addEventListener("click", function() {
            var xhttp = new XMLHttpRequest();

            let contrahentsHoursFrom2 = document.getElementById('inputHourDateFrom');
            let contrahentsHoursTo2 = document.getElementById('inputHourDateTo');
            var getUrl = window.location;
            var baseUrl = getUrl.protocol + "//" + getUrl.host;
            let url = baseUrl + "/ajax-request-get/{{ $workerd->id }}";
            console.log(url)
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var xhttp = new XMLHttpRequest();
                    var all_items = JSON.parse(this.responseText);
                    console.log(all_items)
                    var size = Object.keys(all_items).length;
                    if (size > 0) {
                        var output =
                            " <table class='table'><tr> <th>Kontrahent </th><th>Dzień</th> <th>Godziny</th><th>Edytuj</th><th>Usuń</th> </tr>";
                    } else {
                        var output = "<h4>Brak danych do wyświetlenia</h4>"
                    }
                    let hoursTotal = 0;
                    for (let i = 0; i <= size; i++) {
                        if (all_items[i]) {
                                                    hoursTotal += parseInt(all_items[i]['hours']);

                            if (contrahentsHoursFrom2.value <= all_items[i]['work_day'] && contrahentsHoursTo2
                                .value >= all_items[i]['work_day']) {
                                if (all_items[i]['status_of_billings_worker'] == 1) {
                                    output +=
                                        "<tr><td>" +
                                        all_items[i]["name"] +
                                        "</td>" +
                                        "<td>" +
                                        formatDate(all_items[i]["work_day"]) +
                                        "</td>" +
                                        "<td>" +
                                        all_items[i]["hours"] +
                                        "</td>" +
                                        "<td>Rozliczono</td>" +
                                        "<td>Rozliczono</td>" +
                                        "</tr>";
                                } else {
                                    @if($user->hasPermissionTo('Edytuj/usun godziny pracownika'))
                                    output +=
                                        "<tr><td>" +
                                        all_items[i]["name"] +
                                        "</td>" +
                                        "<td>" +
                                        formatDate(all_items[i]["work_day"]) +
                                        "</td>" +
                                        "<td>" +
                                        all_items[i]["hours"] +
                                        "</td>" +
                                        "<td><a href='" + baseUrl + "/hours/" +
                                        all_items[i]["id"] +
                                        "/edit' class='button-border'>Edytuj</a></td>" +
                                        "<td><a href='" + baseUrl + "/hours/" +
                                        all_items[i]["id"] +
                                        "/delete' class='button-border-del'>Usuń</a></td>" +
                                        "</tr>";
                                    @else
                                    output +=
                                        "<tr><td>" +
                                        all_items[i]["name"] +
                                        "</td>" +
                                        "<td>" +
                                        formatDate(all_items[i]["work_day"]) +
                                        "</td>" +
                                        "<td>" +
                                        all_items[i]["hours"] +
                                        "</td>" +

                                        "<td></td><td></td></tr>";
                                    @endif



                                }

                            } else if (contrahentsHoursFrom2.value == "" && contrahentsHoursTo2.value == "") {

                                if (all_items[i]['status_of_billings_worker'] == 1) {
                                    output +=
                                        "<tr><td>" +
                                        all_items[i]["name"] +
                                        "</td>" +
                                        "<td>" +
                                        formatDate(all_items[i]["work_day"]) +
                                        "</td>" +
                                        "<td>" +
                                        all_items[i]["hours"] +
                                        "</td>" +
                                        "<td>Rozliczono</td>" +
                                        "<td>Rozliczono</td>" +
                                        "</tr>";
                                } else {
                                    @if($user->hasPermissionTo('Edytuj/usun godziny pracownika'))

                                    output +=
                                        "<tr><td>" +
                                        all_items[i]["name"] +
                                        "</td>" +
                                        "<td>" +
                                        formatDate(all_items[i]["work_day"]) +
                                        "</td>" +
                                        "<td>" +
                                        all_items[i]["hours"] +
                                        "</td>" +
                                        "<td><a href='" + baseUrl + "/hours/" +
                                        all_items[i]["id"] +
                                        "/edit' class='button-border'>Edytuj</a></td>" +
                                        "<td><a href='" + baseUrl + "/hours/" +
                                        all_items[i]["id"] +
                                        "/delete' class='button-border-del'>Usuń</a></td>" +
                                        "</tr>";
@else
output +=
                                        "<tr><td>" +
                                        all_items[i]["name"] +
                                        "</td>" +
                                        "<td>" +
                                        formatDate(all_items[i]["work_day"]) +
                                        "</td>" +
                                        "<td>" +
                                        all_items[i]["hours"] +
                                        "</td>" +

                                        "<td></td><td></td></tr>";
@endif

                                }

                            }
                        }
                              if (i === size - 1) {
                            output += `<tr><td><b>Podsumowanie</b></td><td></td><td> <b>${hoursTotal}</b></td><td></td><td></td><td></td></tr>`;
                        }
                    }
                    document.getElementById("result2").innerHTML = output;
                }
            };
            xhttp.open("GET", url, true);
            xhttp.send();
        });

        let buttonGetButton1 = document.querySelector("#paidButton");

        let contrahentsHoursFrom = document.getElementById('inputPaidDateFrom');
        let contrahentsHoursTo = document.getElementById('inputPaidDateTo');


        buttonGetButton1.addEventListener("click", function() {
            var xhttp = new XMLHttpRequest();
            var getUrl = window.location;
            var baseUrl = getUrl.protocol + "//" + getUrl.host;
            let url = baseUrl + "/ajax-request-get-paid/{{ $workerd->id }}";

            xhttp.onreadystatechange = function() {

                if (this.readyState == 4 && this.status == 200) {
                    var all_items = JSON.parse(this.responseText);
                    var size = all_items[0]['bill'].length - 1

                    if (size > -1) {
                        var output2 =
                            "<table class='table'><tr> <th>Okres</th><th>Zaliczki</th><th>Liczba godzin</th> <th>Wypłata</th> <th>Rozliczenie</th> </tr>";
                    } else {
                        var output2 =
                            "<table class='table'><tr> <th>Okres</th><th>Zaliczki</th><th>Liczba godzin</th> <th>Wypłata</th> <th>Rozliczenie</th> </tr>";
                    }
                    let hoursTotal = 0;
                    let paidTotal = 0;
                    let salaryTotal = 0;
                    for (let i = 0; i <= size; i++) {
                        hoursTotal += parseFloat(all_items[0]['bill'][i]['hours']);
                        paidTotal+= parseFloat(all_items[0]['bill'][i]['deposit'])
                        salaryTotal+= parseFloat(all_items[0]['bill'][i]['salary'])

                        console.log(all_items)
                        let total = (parseFloat(all_items[0]['bill'][i]['salary']) - parseFloat(all_items[0][
                                'bill'
                            ]
                            [i]['deposit']))
                        output2 += "<tr><td>" + formatDate(all_items[0]['bill'][i]['date_from']) + " - " +
                            formatDate(all_items[0][
                                    'bill'
                                ]
                                [i][
                                    'date_to'
                                ]) + "<td>" + all_items[0]['bill'][i]['deposit'] +
                            "<td>" + all_items[0]['bill'][i]['hours'] + "<td>" + all_items[0]['bill'][i][
                                'salary'
                            ] + " - " + all_items[0]['bill'][i]['deposit'] + " = " + total +
                            "<td><a class='button-border' href ='" + baseUrl + "/workers/generate-pdf-single/" +
                            all_items[0][
                                'bill'
                            ][i]
                            ['id'] + "'> Drukuj PDF </a></td></tr>";
                            if (i === size -0) {
                            output2 += `<tr><td><b>Podsumowanie</b></td><td><b>${paidTotal}</b></td><td> <b>${hoursTotal}</b></td><td>${salaryTotal}</td><td></td><td></td></tr>`;
                        }

                    }


                    document.getElementById("result").innerHTML = output2;

                } else {
                    document.getElementById("result").innerHTML = "<h4>Brak danych do wyświetlenia</h4>";
                }

            };
            xhttp.open("GET", url, true);
            xhttp.send();
        });
    </script>

@endsection
