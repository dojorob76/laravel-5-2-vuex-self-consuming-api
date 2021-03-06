@extends('layouts.web.web-layout')

@section('web-content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h2 class="page-header">Reset Your {{$site_name}} Password</h2>
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Reset Password</div>
                    <div class="panel-body">
                        @include('authentication.forms.reset-password-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
