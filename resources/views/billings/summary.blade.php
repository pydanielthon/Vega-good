@extends('layouts.app')

@section('content')
    <div class="container-fluid container-summary">

        <!-- <form action="POST"> -->
        <!-- @csrf -->
        <div class="section-title">
            <p class="title-text">Podsumowanie miesiąca</p>


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



            <input type="hidden" value="{{ csrf_token() }}" id="csrftoken">
            <button class="btn btn-primary px-0 add-action-link" id="billingsSummary"><img
                    src='{{ URL::to('/images/iconmonstr-magnifier-6.svg') }}' alt="Usuń pracownika"> <p>Pokaż</p></button>

            <form action="{{ route('billings.generate-pdf-summary') }}" method="POST">
                @csrf
                <input type="hidden" value='' name="frompdf" id="frompdf">
                <input type="hidden" value='' name="topdf" id="topdf">
                <button class="btn-secondary print-pdf add-action-link-green"><img
                        src='{{ URL::to('/images/iconmonstr-magnifier-6.svg') }}' alt="Usuń pracownika"> <p>Pobierz
                    PDF</p></button>
            </form>
            <div id="result-billings" class="table-responsive">


            </div>

        </div>

    </div>
@endsection
