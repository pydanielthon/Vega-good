<!-- need to remove -->
@can('Dostep do pracownikow')

    <li class="nav-li">

        <a href="{{ route('workers.index') }}" class="nav-a active">

            <div class="nav-icon-container"> <i class="nav-icon fas fa-id-card"></i></div>

            <p>Pracownicy</p>
        </a>
        <!-- <a class="nav-a" href="{{ route('workers.create') }}"> Dodaj pracownika</a> -->

    </li>
@endcan
@can('Dostep do kontrahentow')

    <li class="nav-li">
        <a href="{{ route('contrahents.index') }}" class="nav-a active">
            <i class="nav-icon fas fa-users"></i>
            <p>Kontrahenci</p>
        </a>
        <!-- <a class="nav-a" href="{{ route('contrahents.create') }}"> Dodaj kontrahenta</a> -->
    </li>
@endcan
{{-- @can('Can access Hours') --}}
@can('Dodaj godziny')

    <li class="nav-li">
        <a href="{{ route('hours.index') }}" class="nav-a active">
            <i class="nav-icon fas fa-business-time"></i>
            <p>Godziny</p>
        </a>

    </li>
@endcan
{{-- @endcan
@can('Can access Billings') --}}
@can('Dostep do bilansu')

    <li class="nav-li">
        {{-- @can('Can show Billings') --}}


        <a href="{{ route('billings.index') }}" class="nav-a active">
            <i class="nav-icon fas fa-file-invoice-dollar"></i>
            <p>Bilans</p>
        </a>
        {{-- @endcan
    @can('Can add Billings') --}}
        <!-- <a class="nav-a" href="{{ route('billings.create') }}"> Dodaj rozliczenie</a> -->

        {{-- @endcan --}}

    </li>
@endcan
{{-- @endcan --}}

@can('Dostep do bilansu')

    <li class="nav-li">
        <a href="{{ route('hours.list') }}" class="nav-a active">
            <i class="fas fa-history"></i>
            <p>Wszystkie godziny</p>
        </a>
    </li>

    <li class="nav-li">
        <a href="{{ route('billings.summary') }}" class="nav-a active">
            <i class="fas fa-list-alt"></i>
            <p>Podsumowanie miesiąca</p>
        </a>
    </li>
@endcan
@role('Super-admin')
<li class="nav-li">
    <a href="{{ route('users.index') }}" class="nav-a active">
        <i class="nav-icon fas fa-user"></i>
        <p>Użytkownicy</p>
    </a>
    <!-- <a class="nav-a" href="{{ route('users.create') }}"> Dodaj użytkownika</a> -->

</li>

<li class="nav-li">
    <a href="{{ route('logs.index') }}" class="nav-a active">
        <i class="fas fa-user-shield"></i>
        <p>Dziennik zdarzeń</p>
    </a>


</li>
@endrole
