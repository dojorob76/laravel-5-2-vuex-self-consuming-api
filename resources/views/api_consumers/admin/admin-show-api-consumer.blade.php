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
                        This is the <strong>ADMIN</strong> Settings page for API Consumer {{$api_consumer->id}}.
                    </p>
                    @if($api_consumer->isSystemAccount())
                        <div class="alert alert-info text-center">
                            <span class="bold">IMPORTANT ADMIN NOTICE:</span><br>
                            <u>This is a system API account.</u> Any changes made to these settings must also be
                                                                 updated in the system environment variables!!
                        </div>
                    @endif
                    <hr>
                    @if($api_consumer->level === 0)
                        <div class="alert alert-info">
                            <p class="text-center">
                                This API Consumer has not yet activated their API Access Token.<br>The email
                                associated with the account is <em>{{$api_consumer->email}}</em>.
                            </p>
                        </div>
                    @else
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <h4>Refresh API Consumer Access Token</h4>
                            @if($api_consumer->reset_key != null)
                                @include('api_consumers.admin.forms.admin-api-consumer-refresh-token-form')
                            @else
                                @include('api_consumers.admin.forms.admin-api-consumer-reset-key-form')
                            @endif
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <h4>Update API Consumer Settings</h4>
                            @include('api_consumers.admin.forms.admin-update-api-consumer-form')
                        </div>
                    @endif
                </div>

                <hr class="col-sm-10 col-xs-12 col-sm-offset-1 pr0 pl0">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                    <a class="btn btn-danger"
                       href="{{action('Admin\AdminApiConsumerController@destroy', $api_consumer->id)}}"
                    >
                        Delete API Access
                    </a>
                </div>

                <hr class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr0 pl0">
            </div>
        </div>
    </div>
@endsection