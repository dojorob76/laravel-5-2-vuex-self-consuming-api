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
                        Your current API Access Level is <span class="bold">{{$api_consumer->level}}</span>.
                    </p>

                    <hr>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="col-sm-6 col-xs-12">
                            <h4>Refresh API Access Token</h4>
                            @if($api_consumer->reset_key != null)
                                @include('api_consumers.forms.api-consumer-refresh-token-form')
                            @else
                                @include('api_consumers.forms.api-consumer-reset-key-form')
                            @endif
                        </div>

                        <div class="col-sm-6 col-xs-12">
                            <h4>Update API Account Email</h4>
                            @include('api_consumers.forms.update-api-consumer-form')
                        </div>
                    </div>

                    <hr class="col-sm-10 col-xs-12 col-sm-offset-1 pr0 pl0">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <ul class="list-inline text-center">
                            <li class="mb10">
                                <a class="btn btn-warning" href="{{action('ApiConsumerController@getLogout')}}">
                                    Log Out of API Account
                                </a>
                            </li>
                            <li>
                                <a class="btn btn-danger"
                                   href="{{action('ApiConsumerController@destroy', $api_consumer)}}">
                                    Delete API Account
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <hr class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr0 pl0">
            </div>
        </div>
    </div>
@endsection