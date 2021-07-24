@extends('layouts.app')

@section('content')
<div class="container-fluid">
<div class="section-title">
        <p class="title-text">Dodaj kontrahenta</p>
        </div>
    <form action="{{ route('contrahents.store') }}" method="POST" class="add-person">
        @csrf
         <div class="form-row">
            <div class="form-group col-md-7">
                <label for="inputName">Nazwa </label>
                <input type="text" class="form-control" name="name" id="inputName" placeholder="">
            </div>

        </div>
        <div class="form-row">
        <div class="form-group col-md-7">
                <label for="inputSurname">Email</label>
                <input type="email" class="form-control" name="email" id="inputSurname" placeholder="">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-7">
                <label for="inpurPrice">Stawka - Gotówka</label>
                <input type="number" class="form-control" name="salary_cash" id="inputPrice" placeholder="">
            </div>

        </div>
        <div class="form-row">
        <div class="form-group col-md-7">
                <label for="inputSalary">Stawka - Faktura</label>
                <input type="number" class="form-control" name="salary_invoice" id="inputSalary" placeholder="">
            </div>
        </div>
        <div class="form-row">

            <div class="form-group col-md-7">
                <label for="inputSalary">Notatki</label>
                <textarea class="form-control" name="notes" id="inputNotes" rows="3"></textarea>

            </div>
        </div>
        <button type="submit" class="btn center btn-primary add-action-link-green "><img src='{{ URL::to("/images/iconmonstr-plus-6.svg") }}' alt="dodaj pracownika"> <p>Dodaj kontrahenta</p></button>
    </form>

</div>
@endsection
