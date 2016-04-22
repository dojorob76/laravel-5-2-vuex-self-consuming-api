<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
        <!-- Include the App Meta Content for Scripts -->
        @include('layouts.shared.partials._app-meta-content')

        @yield('title')

        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

        <!-- Base JS -->
        <script src="{{ elixir('js/all.js') }}"></script>

        <!-- Include AJAX Setup -->
        @include('global.scripts.ajax-setup')

        <!-- Load additional, page-specific, header JS -->
        @yield('headscripts')

        <!-- Base CSS -->
        <link rel="stylesheet" href="{{ elixir('css/all.css') }}">

        <!-- Load additional, page-specific, header CSS -->
        @yield('headstyles')
    </head>

    <body>
        <!-- Load the Header Navigation -->
        @yield('header-nav')

        <!-- Load the main page content -->
        @yield('content')

        <!-- Include Global JS scripts -->
        @include('global.scripts.global-scripts')

        <!-- Load additional, page-specific, footer JS -->
        @yield('postscripts')
    </body>

</html>