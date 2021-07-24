@extends('layouts.app')

@section('content')
<div class="container-fluid container-summary">

    <!-- <form action="POST"> -->
    <!-- @csrf -->
    <div class="section-title">
        <p class="title-text">Bilans</p>
    @can('Dodaj do bilansu')
    <a href="{{ route('billings.create') }}" class="add-action-link"><img src='{{ URL::to("/images/iconmonstr-plus-6.svg") }}' alt="dodaj wpłatę/wypłatę"> <p>Dodaj do bilansu</p></a>
    @endcan

        </div>
        <div class="table-container">


        <div class="form-row">
            <div class="form-group col-md-7">
            <label for="">Data od</label>
            <input value="" max="{{date('Y-m-d')}}" type="date" class="form-control" name="dateFrom" id="dateFrom"
        placeholder="">
  </div>
  </div>
  <div class="form-row">
            <div class="form-group col-md-7">
            <label for=""> Data do</label>
            <input value="" max="{{date('Y-m-d')}}" type="date" class="form-control" name="dateTo" id="dateTo"
        placeholder="">
            </div></div>
            <div class="form-row">
            <div class="form-group col-md-7">
          <label for="">W opisie </label>   <input type="text" name="inNotes" id="inNotes">
            </div></div>
            <div class="form-row">
            <div class="form-group col-md-7">
                <label for="">Kategoria</label>
            <select name="cat" id="cat">
        <option name="categoryID" value="0" id="all">Sumarycznie
        </option>
        <option name="categoryID" value="s" id="all">zaliczki podsumowanie
        </option>

        @foreach ($category as $cat)
        <option name="categoryID" value="{{$cat->id}}" id="{{$cat->id}}"> {{$cat->name}}
        </option>
        @endforeach
    </select>
</div>
</div>

    <input type="hidden" value="{{ csrf_token() }}" id="csrftoken">
    <input type="hidden" value="{{ route('billings.show',1) }}" id="showurl">
    {{-- <input type="hidden" value="{{ route('billings.edit',1) }}" id="editurl"> --}}
    <input type="hidden" value="{{ route('billings.destroyBilling',1)  }}" id="deleteurl">
    <button class="btn btn-primary px-0 add-action-link" id="billingsButton"><img src='{{ URL::to("/images/iconmonstr-magnifier-6.svg") }}' alt="Usuń pracownika"> <p> Pokaż</p> </button>
    {{-- <input type="hidden" id="data"> --}}
    <!-- </form> -->
    <form action="{{ route('billings.generate-pdf') }}" method="POST">
        @csrf
        <input type="hidden" value='' name="frompdf" id="frompdf">
        <input type="hidden" value='' name="topdf" id="topdf">
        <input type="hidden" value='0' name="categorypdf" id="categorypdf">
        <input type="hidden" value='' name="notespdf" id="notespdf">
        <button class="btn-secondary print-pdf add-action-link-green"><img src='{{ URL::to("/images/iconmonstr-magnifier-6.svg") }}' alt="Usuń pracownika"> <p> Pobierz PDF </p> </button>
        </form>
    <div id="result-billings"></div>
<input type="hidden" value="{{$user}}" id="hasPermissionEdit">
</div>
{{-- <table class="table table-bordered">
    <tr>
        <th>Data</th>
        <th>Kategoria</th>
        <th>Kwota</th>

    </tr>
    @foreach ($billings ?? '' as $billing)
    <tr>
        <td>{{ $billing->date }}</td>
        <td>{{ $billing->category->name }}</td>
        <td>{{ $billing->price }}</td>

        <td>
            <a class="nav-link" href="{{ route('billings.show',$billing->id) }}">Show</a>
            <a class="nav-link" href="{{ route('billings.edit',$billing->id) }}">Edit</a>
            <form action="{{ route('billings.destroy',$billing->id) }}" method="POST">

                @csrf
                @method('DELETE')
                <button type="submit" class="nav-link">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table> --}}
</div>
@endsection
