<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        @include('layouts.shared.nav._navbar-header')

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                <li><a href="{{ url('/home') }}">Home</a></li>
                <li><a href="{{action('ApiConsumerController@index')}}">API</a></li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">Login</a></li>
                    <li><a href="{{ url('/register') }}">Register</a></li>
                @else
                    @can('access-admin-subdomain')
                        <li><a href="{{action('Admin\AdminDashboardController@index')}}">Admin Dashboard</a></li>
                    @endcan
                @endif
                @include('layouts.shared.nav._user-dropdown')
            </ul>
        </div>
    </div>
</nav>