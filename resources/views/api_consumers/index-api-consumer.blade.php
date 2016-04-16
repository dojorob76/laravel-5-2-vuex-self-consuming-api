@extends('app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h2 class="page-header">{{$page_title}}</h2>

                <!-- Display Flash Messages -->
                @include('global.partials._flash-messages')

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-sm-6 col-xs-12">
                        <h4>Create New API Account</h4>
                        @if($api_consumer)
                            @include('api_consumers.partials._has-account-message')
                        @else
                            <p class="text-center">Click the button below to create a new API Account.</p>
                            <a class="btn btn-info center-block add-feedback"
                               href="{{action('ApiConsumerController@create')}}"
                            >
                                @include('global.partials._button-wait')
                                <span class="button-text">Create API Account</span>
                            </a>
                        @endif
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <h4>Manage Existing API Account</h4>
                        @if($api_consumer)
                            <p class="text-center">You are currently logged in to your API Account.</p>
                            @include('api_consumers.partials._manage-account-buttons')
                        @else
                            @include('api_consumers.forms.api-consumer-web-access-form')
                        @endif
                    </div>
                </div>

                <hr class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr0 pl0">
            </div>
        </div>
    </div>
@endsection