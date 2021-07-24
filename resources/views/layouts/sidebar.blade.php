<aside class="main-sidebar sidebar-dark-primary elevation-4" style='background-image:url("{{ URL::to("/images/suchowola-podlaskie-poland-clouds-landscape-water-travel.jpg") }}") '>
    <a href="{{ route('home') }}" class="brand-link">
        <span class="brand-text font-weight-light">VEGA</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @include('layouts.menu')
            </ul>
        </nav>
    </div>

</aside>
