@extends('layouts.web.web-layout')

<!-- Main Content -->
@section('web-content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h2 class="page-header">{{$page_title}}</h2>
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Log In</div>
                    <div class="panel-body">
                        @include('authentication.forms.user-register-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('postscripts')
    @include('authentication.scripts._auth-form-ajax-submit')
@endsection