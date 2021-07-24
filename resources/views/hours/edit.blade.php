@extends('layouts.app')

@section('content')
    <div class="container-fluid hours-container">
        <div class="section-title">
            <p class="title-text">Edytuj godziny</p>
        </div>

        <form action="{{ route('hours.update', $hour['id']) }}" method="POST" class="add-person">

            @csrf
            @if (!empty($hour))
                <div class="form-row">
                    <div class="form-group col-md-7">
                        <label for="inputName">Imię</label>
                        {{ $worker->name }} {{ $worker->surname }}

                    </div>

                </div>
                <div class="form-row">
                    <div class="form-group col-md-7">
                        <input type="hidden" name="workers_price_hour" value="{{ $hour->workers_price_hour }}">
                        <input type="hidden" name="contrahents_salary_cash" value="{{ $hour->contrahents_salary_cash }}">
                        <input type="hidden" name="contrahents_salary_invoice"
                            value="{{ $hour->contrahents_salary_invoice }}">
                        <input type="hidden" name="work_day" value="{{ $hour->work_day }}">
                        <input type="hidden" name="status_of_billings" value="{{ $hour->status_of_billings }}">


                        <label for="inpurPrice" class="d-block">Dzień Pracy</label>
                        <input type="date" class="form-control" max="{{ date('Y-m-d') }}" required id="inputDay" name="inputDay"
                            value="{{ $hour->work_day }}" placeholder="Godziny">

                    </div>

                </div>
                <div class="form-row align-items-center">
                <div class="form-group col-md-7">
                    <label for="inpurPrice" class="d-block">Kontrahent: </label>
                    <select name="inputContrahentName" class="form-control" id="inputContrahentName" >
                        @foreach ($allContrahents as $cont )
                        @if ( !($cont->id == $hour->contrahents_id))
                                   @if ($cont->status == 1)
                                   <option value="{{$cont->id}}">{{$cont->name}}</option>

                                   @else
                                   <option class='inactive-select' value="{{$cont->id}}">{{$cont->name}}</option>

                                   @endif

                            @else
                            @if ($cont->status == 1)
                            <option value="{{$cont->id}}" selected>{{$cont->name}}</option>

                            @else
                            <option class='inactive-select' value="{{$cont->id}}" selected>{{$cont->name}}</option>

                            @endif
                        @endif
                    @endforeach
                    </select>

                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-7">

                        <label for="inputSalary" class="hours-label">Godziny</label>
                        <input type="number" class="form-control" required min="0" id="inputHours" name="inputHours"
                            value="{{ $hour->hours }}" placeholder="Godziny">
                    </div>
                </div>
                <button type="submit" class="btn center btn-primary add-action-link justify-content-center">Zmień</button>

            @endif
            @method('PUT')

        </form>

    </div>
@endsection
