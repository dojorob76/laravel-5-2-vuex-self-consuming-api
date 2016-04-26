@extends('layouts.web.web-layout')

@section('web-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h2 class="page-header">{{$page_title}}</h2>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-sm-6 col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">Create New API Account</div>
                            <div class="panel-body">
                                @if($api_consumer)
                                    @include('api_consumers.partials._has-account-message')
                                @else
                                    <p class="text-center">Click the button below to create a new API Account.</p>
                                    <a class="btn btn-info center-block add-feedback"
                                       href="{{action('ApiConsumerController@create')}}"
                                    >
                                        @include('global.partials._button-wait')
                                        <span class="orig-text">Create API Account</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">Manage Existing API Account</div>
                            <div class="panel-body">
                                @if($api_consumer)
                                    <p class="text-center">You are currently logged in to your API Account.</p>
                                    @include('api_consumers.partials._manage-account-buttons')
                                @else
                                    @include('api_consumers.forms.api-consumer-web-access-form')
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr0 pl0">
            </div>
        </div>
    </div>
@endsection