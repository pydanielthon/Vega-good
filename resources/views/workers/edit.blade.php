@extends('layouts.app')

@section('content')
    <!-- <div class="container-fluid">
                            <form action="{{ route('workers.update', $worker['id']) }}" method="POST">

                                @csrf
                                @if (!empty($worker))

                                    <div class="form-row">

                                        <div class="form-group col-md-5">
                                            <label for="inputName">Imię</label>
                                            <input type="text" class="form-control" name="name" id="inputName" value="{{ $worker->name }}"
                                                placeholder="Imię">
                                        </div>
                                        <div class="form-group col-md-5">
                                            <label for="inputSurname">Nazwisko</label>
                                            <input type="text" class="form-control" name="surname" id="inputSurname"
                                                value="{{ $worker->surname }}" placeholder="Nazwisko">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="inpurPrice">Stawka</label>
                                            <input type="number" class="form-control" name="price_hour" id="inputPrice"
                                                value="{{ $worker->price_hour }}" placeholder="Stawka">
                                        </div>
                                        <div class="form-group col-md-2">

                                            @include('hours.show', ['hours' => $worker->hours, 'id' => $worker->id])
                                            @if ($worker->status == 1)
                                                <a href="{{ URL::to('workers/status/activate', ['id' => $worker->id]) }}"
                                                    class="btn btn-primary disabled">Aktywuj</a>
                                                <a href="{{ URL::to('workers/status/deactivate', ['id' => $worker->id]) }}"
                                                    class="btn btn-primary ">Deaktywuj</a>
                        @else
                                                <a href="{{ URL::to('workers/status/activate', ['id' => $worker->id]) }}"
                                                    class="btn btn-primary ">Aktywuj</a>
                                                <a href="{{ URL::to('workers/status/deactivate', ['id' => $worker->id]) }}"
                                                    class="btn btn-primary disabled">Deaktywuj</a>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="inpurPrice">Adres</label>
                                            <textarea class="form-control" id="inputAddress" name="address"
                                                rows="3">{{ $worker->address }}</textarea>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="inputSalary">Notatki</label>
                                            <textarea class="form-control" id="inputNotes" name="notes"
                                                rows="3">{{ $worker->notes }}</textarea>

                                        </div>

                                    </div>
                                    @method('PUT')

                                    <button type="submit" class="btn center btn-primary">Aktualizuj</button>

                            </form>
                            @endif

                        </div> -->

    <div class="container-fluid form-wrapper">
        <div class="section-title">
            <p class="title-text">Edytuj pracownika</p>
        </div>
        <form action="{{ route('workers.update', $worker['id']) }}" method="POST" class="add-person">

            @csrf
            @if (!empty($worker))

                <div class="form-row">
                    <div class="form-group col-md-7">
                        <label for="inputName">Imię</label>
                        <input type="text" class="form-control" name="name" id="inputName" value="{{ $worker->name }}"
                            minlength="1" placeholder="Imię" required>
                    </div>

                </div>
                <div class="form-row">
                    <div class="form-group col-md-7">
                        <label for="inputSurname">Nazwisko</label>
                        <input type="text" class="form-control" minlength='1' name="surname" id="inputSurname"
                            value="{{ $worker->surname }}" placeholder="Nazwisko" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-7">
                        <label for="inpurPrice">Stawka</label>
                        <input type="number" class="form-control" name="price_hour" min="0" step="0.01" id="inputPrice"
                            placeholder="Stawka" value="{{ $worker->price_hour }}" required>
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
                        <textarea class="form-control" name="address" minlength='1' id="inputAddress" rows="3"
                            required>{{ $worker->address }}</textarea>
                    </div>

                </div>
                <div class="form-row">
                    <div class="form-group col-md-7">
                        <label for="inputSalary">Notatki</label>
                        <textarea class="form-control" name="notes" id="inputNotes"
                            rows="3">{{ $worker->notes }}</textarea>

                    </div>
                </div>
                <div class="form-group col-md-7 status-btns">
                    <label for="">Status</label>
                    @include('hours.show', ['hours' => $worker->hours, 'id' => $worker->id])

                    @if ($worker->status == 1)
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

                    {{-- @if ($worker->status == 1)
                        <a href="{{ URL::to('workers/status/activate', ['id' => $worker->id]) }}"
                            class="btn btn-primary disabled">Aktywuj</a>
                        <a href="{{ URL::to('workers/status/deactivate', ['id' => $worker->id]) }}"
                            class="btn btn-primary ">Deaktywuj</a>
                    @else
                        <a href="{{ URL::to('workers/status/activate', ['id' => $worker->id]) }}"
                            class="btn btn-primary ">Aktywuj</a>
                        <a href="{{ URL::to('workers/status/deactivate', ['id' => $worker->id]) }}"
                            class="btn btn-primary disabled">Deaktywuj</a>
                    @endif --}}


                </div>
                @method('PUT')
                <div class="edit-btns-container">
                    <a href="/workers/{{ $worker->id }}">
                        <button type='button' class="back-btn">Wróć</button>
                    </a>
                    <button type="submit"
                    class="btn center btn-primary add-action-link-green justify-content-center">Aktualizuj</button>
                </div>


        </form>

        @endif
    </div>


@endsection
