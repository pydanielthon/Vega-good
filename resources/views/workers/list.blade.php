@extends('layouts.app')

@section('content')
    <div class="container-fluid ">
        <div class="section-title">
            <p class="title-text">Lista pracowników</p>
            @can('Edytuj pracownika')
                <a href="{{ route('workers.create') }}" class="add-action-link"><img
                        src='{{ URL::to('/images/iconmonstr-plus-6.svg') }}' alt="dodaj pracownika">
                    <p>Dodaj pracownika</p>
                </a>
            @endcan

        </div>
        <div class="table-container">
            <label for="">Szukaj pracownika</label>
            <input type="text" id="search-by-name" class="form-control ">
            <div class="table-responsive">
                <table class="table">
                    <tr class="table-headers">
                        <th>Lp</th>
                        <th>Imię</th>
                        <th>Nazwisko</th>
                        <th>Stawka</th>
                        <th class="text-right">Dane i rozliczenia</th>

                    </tr>
                    <div>
                        @php
                            $count = 0;
                        @endphp
                        @foreach ($workers ?? '' as $worker)
                            @php
                                $count += 1;
                            @endphp
                            @if ($worker->status == 1 and $worker->statusclick == 1)

                                <tr class=" single-table-element" data-info='{{ $worker->name }} {{ $worker->surname }}'
                                    data-link='{{ route('workers.show', $worker->id) }}' data-active='1'>
                                    <td>{{ $count }}</td>
                                    <td>{{ $worker->name }}</td>
                                    <td>{{ $worker->surname }}</td>
                                    <td>{{ $worker->price_hour }}</td>

                                    <td>
                                        @can('Zobacz pracownika')

                                            <a class="nav-link  button-border"
                                                href="{{ route('workers.show', $worker->id) }}">Zobacz</a>
                                        @endcan

                                    </td>
                                </tr>

                            @elseif($worker->status == 1)
                                <tr class=" single-table-element"
                                    data-info='{{ $worker->name }} {{ $worker->surname }}'
                                    data-link='{{ route('workers.show', $worker->id) }}' data-active='1'>
                                    <td>{{ $count }}</td>

                                    <td>{{ $worker->name }}</td>
                                    <td>{{ $worker->surname }}</td>
                                    <td>{{ $worker->price_hour }}</td>

                                    <td>
                                        @can('Zobacz pracownika')
                                            <a class="nav-link  button-border"
                                                href="{{ route('workers.show', $worker->id) }}">Zobacz</a>

                                        @endcan

                                    </td>
                                </tr>

                            @else

                                <tr class="inactive single-table-element"
                                    data-info='{{ $worker->name }} {{ $worker->surname }}'
                                    data-link='{{ route('workers.show', $worker->id) }}' data-active='0'>
                                    <td>{{ $count }}</td>

                                    <td>{{ $worker->name }}</td>
                                    <td>{{ $worker->surname }}</td>
                                    <td>{{ $worker->price_hour }}</td>

                                    <td>
                                        @can('Zobacz pracownika')
                                            <a class="nav-link  button-border"
                                                href="{{ route('workers.show', $worker->id) }}">Zobacz</a>

                                        @endcan

                                    </td>
                                </tr>

                            @endif
                        @endforeach
                    </div>
                </table>
            </div>
        </div>


    </div>
@endsection
