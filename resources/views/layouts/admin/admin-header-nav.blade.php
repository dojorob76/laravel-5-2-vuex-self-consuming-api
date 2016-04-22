<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        @include('layouts.shared.nav._navbar-header')
        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                <li><a href="{{action('Admin\AdminDashboardController@index')}}">Dashboard</a></li>
                <li><a href="{{action('Admin\AdminApiConsumerController@index')}}">API</a></li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <li><a href="{{ $app_root }}">Web App</a></li>
                @include('layouts.shared.nav._user-dropdown')
            </ul>
        </div>
    </div>
</nav>