@extends('layouts.app')

@section('content')
<div class="register-box w-100">

    <div class="section-title">
        <p class="title-text">Dodaj użytkownika</p>
        </div>
    <div class="card">
        <div class="card-body register-card-body ">


            <form method="post" action="{{ route('users.createViaUsers') }}">
                @csrf

                <div class="input-group mb-3 col-md-7">
                    <input type="text"
                           name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           placeholder="Nazwa użytkownika">
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-user"></span></div>
                    </div>
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>
                            Pole jest wymagane

                        </strong>
                    </span>
                    @enderror
                </div>

                <div class="input-group mb-3 col-md-7">
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                    </div>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>
                            @if ($message == 'validation.unique')
                                Email jest już w użyciu
                                @else
                                Pole jest wymagane
                                @endif

                            </strong>
                    </span>
                    @enderror
                </div>

                <div class="input-group mb-3 col-md-7">
                    <input type="password"
                           name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Hasło">
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>  @if ($message == 'validation.confirmed')
                            Hasła muszą być takie same
                            @elseif ($message == 'validation.min.string')
                            Hasło musi mieć min. 8 znaków
                            @else
                            Pole jest wymagane
                        @endif</strong>
                    </span>
                    @enderror
                </div>

                <div class="input-group mb-3 col-md-7">
                    <input type="password"
                           name="password_confirmation"
                           class="form-control"
                           placeholder="Powtórz hasło">
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                </div>

                <div class="row">
                    {{-- <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="agreeTerms" name="terms" value="agree">
                            <label for="agreeTerms">
                                I agree to the <a href="#">terms</a>
                            </label>
                        </div>
                    </div> --}}
                    <!-- /.col -->
                    <div class="col-4">
                        @method('PUT')
                        <button type="submit" class="btn btn-primary btn-block justify-content-center add-action-link">Rejestruj</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

        </div>
        <!-- /.form-box -->
    </div><!-- /.card -->

    <!-- /.form-box -->
</div>
<!-- /.register-box -->

<script src="{{ mix('js/app.js') }}" defer></script>
@endsection
