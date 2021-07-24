@extends('layouts.app')

@section('content')

    <div class="container-fluid workers__show">
        @if (!empty($contrahent))


            <div class="section-title">
                <p class="title-text">{{ $contrahent->name }}</p>
                @can('Usun kontrahenta')
                <form action="{{ route('contrahents.destroy', $contrahent->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Jesteś pewny że chcesz usunąć tego kontrahenta?')"
                        class="nav-link submit-form button-border add-action-link action-delete"><img
                            src='{{ URL::to('/images/iconmonstr-plus-6.svg') }}' alt="Usuń kontrahenta">
                        <p> Usuń
                            kontrahenta</p>
                    </button>
                </form>
                @endcan
            </div>
            <div class="table-container">
                <div class="table-title-container">
                    <p class="table-title">Dane kontrahenta</p>
                    @can('Edytuj kontrahenta')
                        <a class="edit-action" href="{{ route('contrahents.edit', $contrahent->id) }}">Edytuj</a>
                    @endcan
                </div>
                <!-- <p>Nazwa kontrahenta: {{ $contrahent->name }}</p> -->
                @if ($contrahent->email)
                <div class="d-flex align-items-center show-info show-info-wide"> <span>Email kontrahenta: </span>
                    <p class="col-md-7"> {{ $contrahent->email }}</p>
                </div>
                @endif
                <div class="d-flex align-items-center show-info show-info-wide"> <span>Stawka gotówkowa: </span>
                    <p class="col-md-7">{{ $contrahent->salary_cash }}</p>
                </div>
                <div class="d-flex align-items-center show-info show-info-wide"> <span>Stawka fakturowa: </span>
                    <p class="col-md-7">{{ $contrahent->salary_invoice }}</p>
                </div>
                @if ($contrahent->notes)
                    <div class="d-flex align-items-center show-info show-info-wide"> <span>Notatki: </span>
                        <p class="col-md-7">{{ $contrahent->notes }}</p>
                    </div>
                @endif
                    @can('Rozlicz kontrahenta')
                    <div class="table-title-container">
                        <p class="table-title">Rozliczanie godzin</p>
                    </div>
                    <p class="unpaid_hours-container">Kontrahent ma <span id="unpaid_hours">{{ $all_billed_hours ?? '' }}
                        </span> nie rozliczonych godzin. <span id="last_billing">
                            Data ostatniego rozliczenia<b>
                                {{ date('d-m-Y', strtotime($lastBill)) == '01-01-1970' ? 'brak' : date('d-m-Y', strtotime($lastBill)) }}</b>
                        </span>
                    </p>
                    <div class="billing-container-print">
                        <form method="POST" action="{{ route('contrahents.getPaidContr', $contrahent->id) }}">
                            @csrf
                            <p>Rozlicz godziny kontrahenta do dnia</p>

                            <input type="date" max="{{ date('Y-m-d') }}" class="form-control" name="date"
                                value="{{ $today }}" id="inputDateToPaid" value="{{ $today }}">
                            @if ($all_billed_hours > 0)
                                <button class="btn btn-primary add-action-link-green" type="submit" id="paid_worker"
                                    name="button-form" data="billing" value="billing"><i class="fas fa-sync-alt"></i>
                                    <p>Rozlicz</p>
                                </button>
                            @else
                                <button class="btn btn-primary add-action-link-green disabled" disabled type="submit"
                                    id="paid_worker" name="button-form" data="billing" value="billing"><i
                                        class="fas fa-sync-alt"></i>
                                    <p>Rozlicz</p>
                                </button>

                            @endif
                        </form>
                    </div>
                    </p>
                    </p>
                    @endcan




                <div class="table-title-container">
                    <p class="table-title">Lista rozliczeń</p>
                </div>

                <div class="billings-list">

                    Pokaż listę rozliczeń pracownika od <input type="date" max="{{ date('Y-m-d') }}" class="form-control"
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
                    Godziny od: <input type="date" max="{{ date('Y-m-d') }}" class="form-control" name="frompdf55"
                        id="contrahentsHoursFrom" placeholder="">
                    do: <input type="date" max="{{ date('Y-m-d') }}" class="form-control" name="topdf55"
                        id="contrahentsHoursTo" value="{{ $today }}">
                    <button class="btn btn-primary add-action-link-green" id="contrahentsButton"><img
                            src='{{ URL::to('/images/iconmonstr-magnifier-6.svg') }}' alt="pokaż listę">
                        <p>Pokaż</p>
                    </button>
                    {{-- @if ($all_billed_hours > 0)
                        <a class="btn btn-primary add-action-link-green export-all-hours"
                            href="{{ URL::to('contrahents/generate-pdf', ['id' => $contrahent->id]) }}">Eksportuj
                            wszystkie
                            godziny</a>
                    @else
                        <a class="btn btn-primary add-action-link-green disabled export-all-hours"
                            href="{{ URL::to('contrahents/generate-pdf', ['id' => $contrahent->id]) }}"
                            disabled>Eksportuj wszystkie
                            godziny</a>
                    @endif --}}

                </div>
                <input type="hidden" value="{{$hasPerm}}" id="hasPermissionEdit">

                <div id="result2" class='table-responsive'></div>

            </div>
        @endif
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
        let buttonGetButton2 = document.querySelector("#contrahentsButton");
        let contrahentsHoursFrom = document.getElementById('contrahentsHoursFrom');
        let contrahentsHoursTo = document.getElementById('contrahentsHoursTo');
        let fromDate = document.getElementById('frompdf');
        let toDate = document.getElementById('topdf');
        let hasPermission = document.querySelector('#hasPermissionEdit').value
        contrahentsHoursFrom.addEventListener('change', () => {
            fromDate.value = contrahentsHoursFrom.value
        })
        contrahentsHoursTo.addEventListener('change', () => {
            console.log(contrahentsHoursTo.value)
            toDate.value = contrahentsHoursTo.value
        })
        buttonGetButton2.addEventListener("click", function() {
            var getUrl = window.location;
            var baseUrl = getUrl.protocol + "//" + getUrl.host;
            var xhttp = new XMLHttpRequest();
            let url = baseUrl +
                "/ajax-request-get-hours-contrahents/{{ $contrahent->id }}";
            console.log(url)
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var all_items = JSON.parse(this.responseText);
                    var size = Object.keys(all_items).length;
                    if (size > 0) {
                        var output =
                            " <table class='table'><tr> <th>Imię</th><th>Nazwisko</th> <th>Dzień</th> <th>Godziny</th><th>Edytuj</th><th>Usuń</th></tr>";
                    } else {
                        var output =
                            "<h4>Brak danych do wyświetlenia</h4>";
                    }
                    let hoursTotal = 0;
                    for (let i = 0; i < size; i++) {
                        if (contrahentsHoursFrom.value <= all_items[i]['work_day'] && contrahentsHoursTo
                            .value >= all_items[i]['work_day']) {
                            if (all_items[i]['status_of_billings_contrahent'] == 0) {
                                if(hasPermission !=0)
                                {

                                hoursTotal += parseFloat(all_items[i]['hours']);
                                output +=
                                    "<tr>" + "<td>" +
                                    all_items[i]['name'] +
                                    "</td>" +
                                    "<td>" +
                                    all_items[i]['surname'] +
                                    "</td>" +
                                    "<td>" +
                                    formatDate(all_items[i]['work_day']) +
                                    "</td><td style='text-align:left'>" +
                                    all_items[i]['hours'] +
                                    "</td>" +
                                    "<td><a href='" + baseUrl + "/hours/" +
                                    all_items[i]['id'] +
                                    "/edit' class='button-border'>Edytuj</a></td>" +
                                    "<td><a href='" + baseUrl + "/hours/" +
                                    all_items[i]['id'] +
                                    "/delete' class='button-border button-border-del justify-content-start'>Usuń</a></td><td></td>" +
                                    "</tr>";
                                }
                                else{


                                    output +=
                                    "<tr>" + "<td>" +
                                    all_items[i]['name'] +
                                    "</td>" +
                                    "<td>" +
                                    all_items[i]['surname'] +
                                    "</td>" +
                                    "<td>" +
                                    formatDate(all_items[i]['work_day']) +
                                    "</td><td style='text-align:center'>" +
                                    all_items[i]['hours'] +
                                    "</td>" +
                                    "<td></td><td></td></tr>";
                                }
                            } else {
                                output +=
                                    "<tr>" + "<td>" +
                                    all_items[i]['name'] +
                                    "</td>" +
                                    "<td>" +
                                    all_items[i]['surname'] +
                                    "</td>" +
                                    "<td>" +
                                    formatDate(all_items[i]['work_day']) +
                                    "</td><td style='text-align:center'>" +
                                    all_items[i]['hours'] +
                                    "</td>" +
                                    "<td>Rozliczono</td>" +
                                    "<td>Rozliczono</td>" +
                                    "</tr>";
                            }
                        } else if (contrahentsHoursFrom.value == "" && contrahentsHoursTo.value == "") {
                            output +=
                                "<tr>" + "<td>" +
                                all_items[i]['name'] +
                                "</td>" +
                                "<td>" +
                                all_items[i]['surname'] +
                                "</td>" +
                                "<td>" +
                                formatDate(all_items[i]['work_day']) +
                                "</td><td style='text-align:left'>" +
                                all_items[i]['hours'] +
                                "</td>" +
                                "<td><a href='" + baseUrl + "/hours/" +
                                all_items[i]['id'] +
                                "/edit' class='button-border'>Edytuj</a></td>" +
                                "<td><a href='" + baseUrl + "/hours/" +
                                all_items[i]['id'] +
                                "/delete' class='button-border-del'>Usuń</a></td>" +
                                "</tr>";
output +=
                                "<tr>" + "<td>" +
                                all_items[i]['name'] +
                                "</td>" +
                                "<td>" +
                                all_items[i]['surname'] +
                                "</td>" +
                                "<td>" +
                                formatDate(all_items[i]['work_day']) +
                                "</td><td style='text-align:center'>" +
                                all_items[i]['hours'] +
                                "</td>" +
                                "<td></td><td></td></tr>";
                        }
                                if (i === size - 1) {
                            output += `<tr><td><b>Podsumowanie</b></td><td></td><td> <b></b></td><td><b>${hoursTotal}</b></td><td></td><td></td></tr>`;
                        }
                    }
                    document.getElementById("result2").innerHTML = output;
                }
            };
            xhttp.open("GET", url, true);
            xhttp.send();
        });
        let buttonGetPaids = document.querySelector("#paidButton");
        let contrahentsPaidsFrom = document.getElementById('inputPaidDateFrom');
        let contrahentsPaidsTo = document.getElementById('inputPaidDateTo');
        buttonGetPaids.addEventListener('click', () => {
            var getUrl = window.location;
            var baseUrl = getUrl.protocol + "//" + getUrl.host;
            var xhttp = new XMLHttpRequest();
            let url = baseUrl +
                "/ajax-request-get-paids-contrahents/{{ $contrahent->id }}";
            console.log(url)
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var all_items = JSON.parse(this.responseText);
                    var size = all_items[0]['bill'].length
                    let hoursTotal = 0;
                    let paidTotal = 0;
                    var output =
                        " <table class='table'><tr> <th>Okres</th><th>Liczba godzin</th> <th>Rozliczenie</th><th></th><th></th></tr>";
                    for (let i = 0; i < size; i++) {
                        hoursTotal += parseFloat(all_items[0]['bill'][i]['hours'])
                        paidTotal += parseFloat(all_items[0]['bill'][i]['salary'])
                        if (all_items[0]['bill'][i]['date_from'] == '1970-01-01') {
                            output +=
                                "<tr>" + "<td>" +
                                " - " + formatDate(all_items[0]['bill'][i]['date_to']) +
                                "</td>" +
                                "<td>" + all_items[0]['bill'][i]['hours'] +
                                "<td>" +
                                all_items[0]['bill'][i]['salary'] +
                                "</td><td>" +
                                "</td><td><a class='button-border' href ='" + baseUrl +
                                "/contrahents/generate-pdf/" + all_items[0]['bill'][i]['id'] +
                                "'> Drukuj PDF </a></td></tr>";
                        } else {
                            output +=
                                "<tr>" + "<td>" +
                                all_items[0]['bill'][i]['date_from'] + " - " + formatDate(all_items[
                                    0]['bill'][i][
                                    'date_to'
                                ]) +
                                "</td>" +
                                "<td>" + all_items[0]['bill'][i]['hours'] +
                                "<td>" + all_items[0]['bill'][i]['salary'] +
                                "</td><td>" +
                                "</td><td><a class='button-border' href ='" + baseUrl +
                                "/contrahents/generate-pdf/" + all_items[0]['bill'][i]['id'] +
                                "'> Drukuj PDF </a></td></tr>";
                        }
                              if (i === size - 1) {
                            output += `<tr><td><b>Podsumowanie</b></td><td><b>${paidTotal}</b></td><td> <b>${hoursTotal}</b></td><td><b></b></td><td></td><td></td></tr>`;
                        }
                    }
                    document.getElementById("result").innerHTML = output;
                }
            };
            xhttp.open("GET", url, true);
            xhttp.send();
        })
    </script>
@endsection
