@extends('layouts.app')

@section('content')
<div class="container-fluid container-billing">
    <div class="section-title">
        <p class="title-text">Dodaj wpłatę/wypłatę</p>
        </div>
    <form action="{{ route('billings.store') }}" method="POST" class='add-person'>
        @csrf
        <div class="single-billing first-single">

                <div class="form-row">
                <div class="form-group ">
                    <label for="exampleFormControlSelect2" class="wide-label">Wybierz pracownika</label>
                    <select name="workerID[]" class="form-control " id="exampleFormControlSelect2">
                        <option value=""> Nie dotyczy
                        </option>
                        @foreach ($workers as $worker)
                        @if ($worker->status == 1 and $worker->statusclick== 1)

                        <option  value="{{$worker->id}}"> {{$worker->name}} {{$worker->surname}}
                        </option>
                        @elseif($worker->status)

                        <option  value="{{$worker->id}}"> {{$worker->name}} {{$worker->surname}}
                        </option>
                        @else
                        <option  class="inactive-select" value="{{$worker->id}}"> {{$worker->name}} {{$worker->surname}}
                        </option>
                        @endif



                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group ">
                    <label for="exampleFormControlSelect22" class="wide-label">Wybierz kontrahenta</label>
                    <select name="contrahentID[]" class="form-control" id="exampleFormControlSelect22">
                        <option  value=""> Nie dotyczy
                        </option>
                        @foreach ($contrahents as $contrahent)

                        @if ($contrahent->status and $contrahent->statusclick)

                        <option  value="{{$contrahent->id}}"> {{$contrahent->name}}
                        </option>
                        @elseif($contrahent->status)
                        <option  value="{{$contrahent->id}}"> {{$contrahent->name}}
                        </option>
                        @else
                        <option  class="inactive-select" value="{{$contrahent->id}}"> {{$contrahent->name}}
                        </option>
                        @endif

                        @endforeach

                    </select>
                </div>
            </div>


            <div class="form-row">
                <div class="form-group ">
                    <label for="inpurPrice">Kwota</label>
                    <input type="number" required min="0" step="0.01" class="form-control" name="price[]" id="inputPrice" placeholder="">
                </div>

            </div>
            <div class="form-row">
                <div class="form-group ">
                    <label for="inpurPrice">Data </label>
                    <input type="date" required max="{{date('Y-m-d')}}" class="form-control" name="date[]" id="inputDate" placeholder="">
                </div>
            </div>

        <div class="form-row">
            <div class="form-group ">
                <label for="exampleFormControlSelect22" class="wide-label">Wybierz kategorię</label>
                <select name="categoryID[]" class="form-control" required id="categoryID">
                    @foreach ($category as $cat)
                    <option name="categoryID" value="{{$cat->id}}" id="{{$cat->id}}"> {{$cat->name}}
                    </option>
                    @endforeach

                </select>
            </div>
        </div>
        <div class="form-row notes-row">

            <div class="form-group">
                <label for="inputSalary">Notatki</label>
                <textarea class="form-control" name="notes[]" id="inputNotes" rows="3"></textarea>

            </div>

        </div>
        </div>
        <button type="button" class="add-billing-btn d-block  button-border mr-15">Dodaj wiersz</button>

        <button type="submit" class="btn center btn-primary add-action-link-green"><img src='{{ URL::to("/images/iconmonstr-plus-6.svg") }}' alt="dodaj wpłatę"><p>Dodaj</p></button>
    </form>

</div>
@endsection
