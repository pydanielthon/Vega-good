@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="section-title">
            <p class="title-text">Edytuj kontrahenta</p>
        </div>
        <form action="{{ route('contrahents.update', $contrahent['id']) }}" method="POST" class="add-person">

            @csrf
            <div class="form-row">
                <div class="form-group col-md-7">
                    <label for="inputName">Nazwa </label>
                    <input type="text" class="form-control" name="name" id="inputName" placeholder=""
                        value="{{ $contrahent->name }}">
                </div>

            </div>
            <div class="form-row">
                <div class="form-group col-md-7">
                    <label for="inputSurname">Email</label>
                    <input type="email" class="form-control" name="email" id="inputSurname" placeholder=""
                        value="{{ $contrahent->email }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-7">
                    <label for="inpurPrice">Stawka - Gotówka</label>
                    <input type="number" class="form-control" name="salary_cash" id="inputPrice" placeholder=""
                        value="{{ $contrahent->salary_cash }}">
                </div>

            </div>
            <div class="form-row">
                <div class="form-group col-md-7">
                    <label for="inputSalary">Stawka - Faktura</label>
                    <input type="number" class="form-control" name="salary_invoice" id="inputSalary" placeholder=""
                        value="{{ $contrahent->salary_invoice }}">
                </div>
            </div>
            <div class="form-row">

                <div class="form-group col-md-7">
                    <label for="inputSalary">Notatki</label>
                    <textarea class="form-control" name="notes" id="inputNotes"
                        rows="3">{{ $contrahent->notes }}</textarea>

                </div>
            </div>
            <div class="form-group col-md-7 status-btns">

                @include('hours.show', ['hours' => $contrahent->hours, 'id' => $contrahent->id])
                <label for="">Status</label>
                @if ($contrahent->status == 1)
                <div class="slider-category slider-category-specializations">
                    <button type='button' class="slider-category__button slider-category-active slider-button" data-status="active">Aktywuj</button>
                    <button type='button' class="slider-category__button slider-button" data-status="inactive">Deaktywuj</button>
                </div>
                <input type="hidden" name='status' id="status" value='1'>
                    {{-- <a href="{{ URL::to('contrahent/status/activate', ['id' => $contrahent->id]) }}"
                        class="btn btn-primary disabled">Aktywuj</a>
                    <a href="{{ URL::to('contrahent/status/deactivate', ['id' => $contrahent->id]) }}"
                        class="btn btn-primary ">Deaktywuj</a> --}}
                @else
                <div class="slider-category slider-category-specializations">
                    <button type='button' class="slider-category__button slider-button" data-status="active">Aktywuj</button>
                    <button type='button' class="slider-category__button slider-button slider-category-active slider-button" data-status="inactive">Deaktywuj</button>
                </div>
                <input type="hidden" name='status' id="status" value='0'>
                    {{-- <a href="{{ URL::to('contrahent/status/activate', ['id' => $contrahent->id]) }}"
                        class="btn btn-primary ">Aktywuj</a>
                    <a href="{{ URL::to('contrahent/status/deactivate', ['id' => $contrahent->id]) }}"
                        class="btn btn-primary disabled">Deaktywuj</a> --}}
                @endif
            </div>
            @method('PUT')
            <div class="edit-btns-container">
                <a href="/contrahents/{{ $contrahent->id }}">
                    <button type='button' class="back-btn">Wróć</button>
                </a>
                <button type="submit"
                class="btn center btn-primary add-action-link-green justify-content-center">Aktualizuj</button>
            </div>
            {{-- <button type="submit"
                class="btn center add-action-link-green justify-content-center btn-primary">Aktualizuj</button>
                <a href="/contrahents/{{ $contrahent->id }}">
                    <button type='button' class="button-border">Wróć</button>
                </a> --}}
            </form>

    </div>
@endsection
