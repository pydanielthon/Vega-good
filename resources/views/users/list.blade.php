@extends('layouts.app')

@section('content')
<div class="container-fluid">
<div class="section-title">
        <p class="title-text">Lista użytkowników</p>
        <a href="{{ route('users.create') }}" class="add-action-link"><img src='{{ URL::to("/images/iconmonstr-plus-6.svg") }}' alt="dodaj pracownika"> Dodaj użytkownika</a>

    </div>
<div class="table-container table-responsive">
    <table class="table">
        <tr class="table-headers">
            <th>Nazwa</th>
            <th>Email</th>
            <th>Edycja</th>
        </tr>
        @foreach ($users ?? '' as $user)
        <tr>



            <td>{{ $user->name }}</td>

            <td>{{ $user->email }}</td>

            <td>

                <a class="nav-link button-border" href="{{ route('users.edit',$user->id) }}">Edytuj</a>



            </td>
        </tr>
        @endforeach
    </table>
    </div>
</div>
@endsection
