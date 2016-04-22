@extends('layouts.web.web-layout')

@section('web-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h2 class="page-header">{{$page_title}}</h2>

                @if($api_consumer)
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        @include('api_consumers.partials._has-account-message')
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        @include('api_consumers.partials._manage-account-buttons')
                    </div>
                @else
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <p class="text-center">
                            To access the {{$site_name}} API, you will need an API Access Token. Please enter your valid
                            email address in the form below to get started.
                        </p>
                    </div>

                    <div class="col-sm-8 col-sm-offset-2">
                        <div class="panel panel-default">
                            <div class="panel-heading">Create New API Account</div>
                            <div class="panel-body">
                                @include('api_consumers.forms.create-api-consumer-form')
                            </div>
                        </div>
                    </div>
                @endif

                <hr class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr0 pl0">
            </div>
        </div>
    </div>
@endsection