@extends('layouts.app')

@section('content')
    <div class="container-fluid">
    <div class="section-title">
        <p class="title-text">Dziennik zdarzeń</p>
        </div>
    <div class="table-container table-responsive">

        <table class="table table-bordered ">
            <tr class="table-headers">
                <th>Akcja</th>
                <th>Data</th>
                <th>Użytkownik</th>
                <th>Pracownik</th>
                <th>Kontrahent</th>
                <th>Dodatkowe informacje</th>
            </tr>
            @foreach ($logs ?? '' as $log)
                <tr>
                    <td>{{ $log->logName }} </td>
                    <td>{{ $log->logDate }} </td>
                    <td>{{ $log->userEmail }}</td>
                    <td> {{ $log->workerName }} {{ $log->workerSurname }}
                        {{ !empty($log->worker_id) ? '(' . $log->worker_id . ')' : '-' }} </td>
                    <td> {{ $log->contrahentName }}
                        {{ !empty($log->contrahent_id) ? '(' . $log->contrahent_id . ')' : '-' }} </td>
                    <td> {{ !empty($log->notes) ? $log->notes : '-' }} </td>
                </tr>
            @endforeach
        </table>

        <div class='pagination'>   {{$logs->links()}}</div>

    </div>
</div>
@endsection
