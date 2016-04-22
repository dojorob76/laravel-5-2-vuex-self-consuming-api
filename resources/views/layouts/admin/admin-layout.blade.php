@extends('layouts.app')

@section('title')
    <title>@if(isset($page_title)){{$page_title}}@else{{$site_name}} Administration @endif</title>
@endsection

@section('header-nav')
    @include('layouts.admin.admin-header-nav')
@endsection

@section('content')
    <!-- Display Flash Messages -->
    @include('layouts.shared.partials._flash-messages')
    @yield('admin-content')
@endsection