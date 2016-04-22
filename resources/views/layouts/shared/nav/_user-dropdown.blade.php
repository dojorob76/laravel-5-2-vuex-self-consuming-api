@if(Auth::check())
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
            {{ Auth::user()->name }} <span class="caret"></span>
        </a>

        <ul class="dropdown-menu" role="menu">
            <li>
                <a href="{{ url($app_root . '/logout') }}">
                    <span class="glyphicon glyphicon-log-out"></span> Logout
                </a>
            </li>
        </ul>
    </li>
@endif