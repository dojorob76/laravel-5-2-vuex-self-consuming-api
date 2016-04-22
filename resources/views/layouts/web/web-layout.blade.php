@extends('layouts.app')

@section('title')
    <title>@if(isset($page_title)){{$page_title}}@else{{$site_name}}@endif</title>
@endsection

@section('header-nav')
    @include('layouts.web.header-nav')
@endsection

@section('content')
    <!-- Display Flash Messages -->
    @include('layouts.shared.partials._flash-messages')

    @yield('web-content')
@endsection