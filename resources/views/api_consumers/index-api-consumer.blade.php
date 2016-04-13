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
                            <p class="text-center">
                                You already have an API Account.<br>You may access it by clicking the 'Manage My API
                                Account' button <span class="hidden-xs">on the right</span>
                                <span class="hidden-lg hidden-md hidden-sm">below</span>.
                            </p>
                        @else
                            <p class="text-center">Click the button below to create a new API Account.</p>
                            <a class="btn btn-info center-block" href="{{action('ApiConsumerController@create')}}">
                                Create API Account
                            </a>
                        @endif
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <h4>Manage Existing API Account</h4>
                        @if($api_consumer)
                            <p class="text-center">You are currently logged in to your API Account.</p>
                            <ul class="list-inline text-center">
                                <li class="mb10">
                                    <a class="btn btn-primary"
                                       href="{{action('ApiConsumerController@show', $api_consumer->id)}}"
                                    >
                                        Manage My API Account
                                    </a>
                                </li>
                                <li>
                                    <a class="btn btn-warning"
                                       href="{{action('ApiConsumerController@getLogout')}}"
                                    >
                                        Log Out of API Account
                                    </a>
                                </li>
                            </ul>
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