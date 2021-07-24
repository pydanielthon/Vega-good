@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <form action="{{ route('category.store') }}" method="POST">
        @csrf <div class="form-row">
            <div class="form-group col-md-12">
                <label for="inputName">Nazwa </label>
                <input type="text" class="form-control" name="name" id="inputName" placeholder="">
            </div>

        </div>

        <button type="submit" class="btn center btn-primary">Dodaj</button>
    </form>

</div>
@endsection
