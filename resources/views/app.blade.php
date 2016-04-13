<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
        <meta name="session-domain" id="session-domain" content="{{env('SESSION_DOMAIN')}}">
        <meta name="app-main" id="app-main" content="{{env('APP_MAIN')}}">
        <meta name="url-protocol" id="url-protocol" content="{{env('URL_PROTOCOL')}}">

        <title>@if(isset($page_title)){{$page_title}}@else{{$site_name}}@endif</title>

        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

        <!-- Base JS -->
        <script src="{{ elixir('js/all.js') }}"></script>

        <!-- Load additional, page-specific, header JS -->
        @yield('headscripts')

        <!-- Base CSS -->
        <link rel="stylesheet" href="{{ elixir('css/all.css') }}">

        <!-- Load additional, page-specific, header CSS -->
        @yield('headstyles')
    </head>

    <body>

        <!-- Load the main page content -->
        @yield('content')

        <!-- Load additional, page-specific, footer JS -->
        @yield('postscripts')

    </body>

</html>