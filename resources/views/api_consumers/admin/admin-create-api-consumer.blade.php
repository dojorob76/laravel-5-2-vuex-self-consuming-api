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
                        To create a new API Consumer, please enter a valid email address in the field below.
                    </p>
                </div>

                <hr class="col-sm-10 col-xs-12 col-sm-offset-1" style="padding-right: 0; padding-left: 0;">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    @include('api_consumers.admin.forms.admin-create-api-consumer-form')
                </div>

                <hr class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-right: 0; padding-left: 0;">
            </div>
        </div>
    </div>
@endsection