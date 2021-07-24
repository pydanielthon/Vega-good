@extends('layouts.app')

@section('content')
<div class="container-fluid">
<div class="section-title">
        <p class="title-text add-action-link-delete">Twój profil</p>

        </div>
    <form method="POST" action="{{ route('users.update', $User->id) }}" class="add-person">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-7">
                <label for="inputName">Nazwa</label>
                <input type="text" name="name" readonly disabled class="form-control" value='{{$User->name}}' id="inputName"
                    placeholder="Nazwa">
            </div>

        </div>
        <div class="form-row">
            <div class="form-group col-md-7">
                <label for="inpurPrice">Email</label>
                <input type="email" name="email" readonly disabled class="form-control" value='{{$User->email}}' id="inputPrice"
                    placeholder="Email">
                    @error('email')
                        <p>Podany email jest zajety lub jest niepoprawny</p>
                    @enderror
            </div>

        </div>
        <div class="table-title-container"> <p class="table-title">Haslo</p></div>
        <a href="{{ route('password.request') }}" class="table-reset-user">Reset hasła</a>

        <div class="table-title-container"> <p class="table-title">Uprawnienia</p></div>

        <div class="form-row user-permissions">
            <div class="form-group col-md-7 align-items-start flex-column">
                @foreach ($User->permissions as $perm)

                {{-- <p>{{$perm}}</p> --}}

                @endforeach
                @foreach ($P as $perm)

                <div> <label for='{{Str::slug($perm->name, "-")}}'>{{$perm->name}}</label>
                    @if($User->hasPermissionTo($perm->id))
                    <input type="checkbox"  readonly disabled name='perms[]' class="" value='{{$perm->id}}'
                        id='{{Str::slug($perm->name, "-")}}' checked>

                    @else
                    <input type="checkbox"  readonly disabled name='perms[]' class="" value='{{$perm->id}}'
                        id='{{Str::slug($perm->name, "-")}}'>
                    @endif
                </div>

                @endforeach
            </div>

        </div>
        <div class="table-title-container"> <p class="table-title">Rola super admina</p></div>


        <div> <label for='superadmin' class='superadmin'>Super admin</label>
            @if($User->hasRole('Super-admin'))
            <input type="checkbox" name='superadmin' class=""  readonly disabled value='{{$perm->id}}' id='superadmin' checked>

            @else
            <input type="checkbox" name='superadmin' class=""  readonly disabled value='{{$perm->id}}' id='superadmin'>

            @endif
        </div>


        {{-- <button type="submit" class="btn center btn-primary add-action-link-green justify-content-center">Aktualizuj</button> --}}
        @method('PUT')



    </form>

</div>
@endsection
