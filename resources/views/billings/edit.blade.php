@extends('layouts.app')

@section('content')
    <div class="container-fluid edit-summary">
        <div class="section-title">
            <p class="title-text">Edytuj wpłatę/wypłatę</p>
            </div>
        <form method="POST" action="{{ route('billings.update', $billing->id) }}" class="add-person">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-7">
                    <label for="inputName">Pracownik</label>
                    <select name="workers_id" id="worker" class="form-select form-control"  readonly disabled>
                        <option  value=""> Nie dotyczy
                        </option>
                        @foreach ($workers as $worker)
                    @if (!($billing->workers_id == $worker->id))
                    <option  value="{{$worker->id}}" > {{$worker->name}} {{$worker->surname}}
                    </option>
                    @else
                    <option  value="{{$worker->id}}"  selected="selected"> {{$worker->name}} {{$worker->surname}}
                    </option>
                    @endif
                        @endforeach
                    </select>
                </div>

            </div>
            <div class="form-row">
                <div class="form-group col-md-7">
                    <label for="inpurPrice">Kontrahent</label>
                    <select name="contrahents_id" readonly class="form-select form-control" disabled id="contrahent">
                        <option  value=""> Nie dotyczy
                        </option>
                        @foreach ($contrahents as $contrahent)
                    @if (!($billing->contrahents_id == $contrahent->id))

                    <option value="{{$contrahent->id}}" > {{$contrahent->name}}
                    </option>
                    @else
                    <option  data-test="  {{$billing->contrahents_id }} {{$contrahent->id}}" value="{{$contrahent->id}}"  selected="selected"> {{$contrahent->name}}
                    </option>
                    @endif
                        @endforeach
                    </select>
                </div>

            </div>
            <div class="form-row">
                <div class="form-group col-md-7">
                    <label for="inpurPrice">Kwota</label>
                    <input type="number" name="price" readonly min="0" step="0.01" class="form-control" value='{{$billing->price}}' id="inputPrice"
                        placeholder="price">
                </div>

            </div>
            <div class="form-row">
                <div class="form-group col-md-7">
                    <label for="inpurDate">Data</label>
                    <input type="date"  max="{{date('Y-m-d')}}" readonly name="date" class="form-control" value='{{$billing->date}}' id="inputDate"
                      >
                </div>

            </div>
            <div class="form-row">
                <div class="form-group col-md-7">
                    <label for="inpurPrice">Notatki</label>
                    <textarea name="notes" readonly class="form-control" value='' id="inputNotes"
                       >{{$billing->notes}}</textarea>
                </div>

            </div>

            <div class="form-row">
                <div class="form-group col-md-7">
                    <label for="">Kategoria</label>
                    <select name="category_id" id="cat" class="form-control form-select" disabled readonly>

                        @foreach ($category as $cat)
                        @if (!($cat->id == $billing->category_id))
                        <option name="categoryID" value="{{$cat->id}}" id="{{$cat->id}}"> {{$cat->name}}
                        </option>
                        @else
                        <option name="categoryID" value="{{$cat->id}}" id="{{$cat->id}}" selected="selected"> {{$cat->name}}
                        </option>
                        @endif

                        @endforeach
                    </select>
                </div>

            </div>
            @method('PUT')
            <button type="submit" class="btn center btn-primary add-action-link-green  justify-content-center  update-billing-btn">Aktualizuj</button>



            <button type='button' class="able-to-edit btn-primary add-action-link justify-content-center  ">Edytuj</button>

        </form>
    </div>
@endsection
