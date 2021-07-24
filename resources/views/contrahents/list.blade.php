@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="section-title">
            <p class="title-text">Lista kontrahentów</p>
            @can('Edytuj kontrahenta')
                <a href="{{ route('contrahents.create') }}" class="add-action-link"><img
                        src='{{ URL::to('/images/iconmonstr-plus-6.svg') }}' alt="dodaj pracownika"> Dodaj kontrahenta</a>
            @endcan
        </div>
        <div class="table-container">
            <label for="">Szukaj kontrahenta</label>
            <input type="text" id="search-by-name" class="form-control search-contrahents">
            <div class="table-responsive">
                <table class="table">
                    <tr class='table-headers'>
                        <th>Lp</th>
                        <th>Firma</th>
                        <th>Email</th>
                        <th class="text-right">Dane i rozliczenia</th>

                    </tr>
                    @php
                        $count = 0;
                    @endphp
                    @foreach ($contrahents ?? '' as $contrahent)
                        @php
                            $count += 1;
                        @endphp
                        @if ($contrahent->status == 1 and $contrahent->statusclick == 1)

                            <tr class='single-table-element' data-info='{{ $contrahent->name }}'
                                data-link='{{ route('contrahents.show', $contrahent->id) }}' data-active='1'>
                                <td>{{ $count }}</td>
                                <td>{{ $contrahent->name }}</td>
                                <td>{{ $contrahent->email }}</td>
                                <td>
                                    @can('Zobacz kontrahenta')

                                        <a class="nav-link button-border"
                                            href="{{ route('contrahents.show', $contrahent->id) }}">Zobacz</a>
                                    @endcan

                                </td>
                            </tr>
                        @elseif($contrahent->status == 1)


                            <tr class='single-table-element' data-info='{{ $contrahent->name }}'
                                data-link='{{ route('contrahents.show', $contrahent->id) }}' data-active='1'>
                                <td>{{ $count }}</td>
                                <td>{{ $contrahent->name }}</td>
                                <td>{{ $contrahent->email }}</td>
                                <td>
                                    @can('Zobacz kontrahenta')
                                        <a class="nav-link button-border"
                                            href="{{ route('contrahents.show', $contrahent->id) }}">Zobacz</a>
                                    @endcan

                                </td>
                            </tr>
                        @else

                            <tr class="inactive single-table-element" data-info='{{ $contrahent->name }}'
                                data-link='{{ route('contrahents.show', $contrahent->id) }}' data-active='0'>
                                <td>{{ $count }}</td>
                                <td>{{ $contrahent->name }}</td>
                                <td>{{ $contrahent->email }}</td>
                                <td>
                                    @can('Dostep do kontrahentow')
                                        <a class="nav-link button-border"
                                            href="{{ route('contrahents.show', $contrahent->id) }}">Zobacz</a>
                                    @endcan

                                </td>
                            </tr>

                        @endif
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
