@extends('app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h2 class="page-header">{{$page_title}}</h2>

                <!-- Display Flash Messages -->
                @include('global.partials._flash-messages')

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p class="text-center">
                        To access the {{$site_name}} API, you will need an API Access Token. Please enter your valid
                        email address in the field below to get started.
                    </p>
                </div>

                <hr class="col-sm-10 col-xs-12 col-sm-offset-1 pr0 pl0">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    @include('api_consumers.forms.create-api-consumer-form')
                </div>

                <hr class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr0 pl0">
            </div>
        </div>
    </div>
@endsection