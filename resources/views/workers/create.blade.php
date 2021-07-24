@extends('layouts.app')

@section('content')
    <div class="container-fluid form-wrapper">
    <div class="section-title">
        <p class="title-text">Dodaj pracownika</p>
        </div>
        <form action="{{ route('workers.store') }}" method="POST" class="add-person">
            @csrf <div class="form-row">
                <div class="form-group col-md-7">
                    <label for="inputName">Imię</label>
                    <input type="text" class="form-control" minlength='1' name="name" id="inputName" placeholder="Imię" required>
                </div>

            </div>
            <div class="form-row">
            <div class="form-group col-md-7">
                    <label for="inputSurname">Nazwisko</label>
                    <input type="text" class="form-control" minlength='1' name="surname" id="inputSurname" placeholder="Nazwisko" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-7">
                    <label for="inpurPrice">Stawka</label>
                    <input type="number" class="form-control" name="price_hour" min="0" step="0.01" id="inputPrice" placeholder="Stawka" required>
                </div>

            </div>
                <div class="form-row">
                <div class="form-group col-md-7">
                    <label for="inpurPrice">Stawka za prąd</label>
                    <input type="number" class="form-control" name="electric_price" min="0" step="0.01" id="inputPriceElectricity" value="20" required>
                </div>

            </div>
            <div class="form-row">
                <div class="form-group col-md-7">
                    <label for="inpurPrice">Adres</label>
                    <textarea class="form-control" name="address" minlength='1' id="inputAddress" rows="3" required></textarea>
                </div>

            </div>
    <div class="form-row">
    <div class="form-group col-md-7">
                    <label for="inputSalary">Notatki</label>
                    <textarea class="form-control" name="notes" id="inputNotes" rows="3"></textarea>

                </div>
    </div>
            <button type="submit" class="btn center btn-primary add-action-link-green "><img src='{{ URL::to("/images/iconmonstr-plus-6.svg") }}' alt="dodaj pracownika"><p> Dodaj pracownika</p></button>
        </form>

    </div>
@endsection
