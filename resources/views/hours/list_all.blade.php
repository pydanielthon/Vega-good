@extends('layouts.app')

@section('content')
    <div class="container-fluid container-summary">

        <!-- <form action="POST"> -->
        <!-- @csrf -->
        <div class="section-title">
            <p class="title-text">Lista godzin </p>
        </div>
        <div class="table-container">


            <div class="form-row">
                <div class="form-group col-md-7">
                    <label for="">Data od</label>
                    <input value="" max="{{ date('Y-m-d') }}" type="date" class="form-control" name="dateFrom"
                        id="dateFrom" placeholder="">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-7">
                    <label for=""> Data do</label>
                    <input value="" max="{{ date('Y-m-d') }}" type="date" class="form-control" name="dateTo" id="dateTo"
                        placeholder="">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-7">
                    <label for="">Wyświetl</label>
                    <select name="cat" id="cat">
                        <option name="categoryID" value="0" id="all">Kontrahentów
                        </option>
                        <option name="categoryID" value="1" id="all">Pracowników
                        </option>

                    </select>
                </div>
            </div>

            <input type="hidden" value="{{ csrf_token() }}" id="csrftoken">
            {{-- <input type="hidden" value="{{ route('billings.edit',1) }}" id="editurl"> --}}
            <input type="hidden" value="{{ route('billings.destroyBilling', 1) }}" id="deleteurl">
            <button class="btn btn-primary mb-3 add-action-link" id="hoursAll"><img
                    src='{{ URL::to('/images/iconmonstr-magnifier-6.svg') }}'> <p>Pokaż</p></button>
            {{-- <input type="hidden" id="data"> --}}
            <!-- </form> -->
            <form action="{{ route('billings.generate-pdf') }}" method="POST">
                @csrf

                <input type="hidden" value="{{ route('hours.show', 1) }}" id="showurl">


            </form>
            <div id="result-billings" class="table-responsive"></div>

        </div>

    </div>
@endsection
