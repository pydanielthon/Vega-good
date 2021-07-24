@extends('layouts.app')

@section('content')
    <div class="container-fluid container-hour">
    <div class="section-title">
        <p class="title-text">Dodaj godziny</p>
        </div>
        <form action="{{ route('hours.store') }}" method="POST" class="add-person">
            @csrf
            <div class="form-group choose-worker">
                <label for="exampleFormControlSelect2 ">Pracownik</label>
                <select name="workerID" id="select-spec" class="hero__select select-active" data-cat="specjalizacje">                    @foreach ($workers as $worker)
                        @if ($worker->status == 1 and $worker->statusclick ==1)

                        <option value="{{ $worker->id }}"> {{ $worker->name }}
                            {{ $worker->surname }}
                        </option>
                        @elseif($worker->status)
                        <option value="{{ $worker->id }}"> {{ $worker->name }}
                            {{ $worker->surname }}
                        </option>
                        @else


                        <option value="{{ $worker->id }}" class='inactive-select'> {{ $worker->name }}
                            {{ $worker->surname }}
                        </option>
                        @endif

                    @endforeach

                </select>
            </div>

                <div class="single-hour first-single">

                    <div class="form-row">


                        <div class="form-group ">
                            <label for="">Kontrahent</label>
                            <select name="contrahentID[]" class="testy form-control stest" required >
                                @foreach ($contrahents as $contrahent)
                                @if ($contrahent->status and $contrahent->statusclick==1)
                                <option  value="{{ $contrahent->id }}"
                                   >
                                    {{ $contrahent->name }}
                                </option>
                                @elseif($contrahent->status)
                                <option  value="{{ $contrahent->id }}"
                                    >
                                     {{ $contrahent->name }}
                                 </option>
                                @else
                                <option  value="{{ $contrahent->id }}"
                                    class="inactive-select">
                                    {{ $contrahent->name }}
                                </option>
                                @endif

                                @endforeach

                            </select>
                        </div>

                    </div>
                    <div class="form-row">
                    <div class="form-group ">
                            <label for="inputSalary">Ilość godzin</label>
                            <input type="number" required min="0" step="1" class="form-control" name="hours[]"
                                id="inputSalary" placeholder="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group ">
                            <label for="inpurPrice">Data</label>
                            <input type="date" max="{{ date('Y-m-d') }}" required class="form-control" name="work_day[]"
                                id="inputPrice" placeholder="">
                        </div>

                    </div>
                </div>

            <button type="button" class="add-hours-btn d-block button-border">Dodaj wiersz</button>
            <button type="submit" class="btn center btn-primary add-action-link-green"><img src='{{ URL::to("/images/iconmonstr-plus-6.svg") }}' alt="dodaj godziny"><p>Dodaj godziny</p></button>
        </form>

    </div>
@endsection
