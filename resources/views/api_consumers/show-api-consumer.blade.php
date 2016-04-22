@extends('layouts.web.web-layout')

@section('web-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h2 class="page-header">{{$page_title}}</h2>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p class="text-center">
                        Your current API Access Level is <span class="bold">{{$api_consumer->level}}</span>.
                    </p>

                    <hr>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="col-sm-6 col-xs-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">Refresh API Access Token</div>
                                <div class="panel-body">
                                    @if($api_consumer->reset_key != null)
                                        @include('api_consumers.forms.api-consumer-refresh-token-form')
                                        <hr>
                                        <p class="text-center">
                                            <strong>Attention:</strong> Please allow several minutes for your Reset
                                            Key email to arrive and be sure to check your spam folders.
                                        </p>

                                        <p class="text-center">
                                            Still haven't received your email?<br>Click the button below to request a
                                            new one.
                                        </p>
                                        @include('api_consumers.forms.api-consumer-resend-reset-key-form')
                                    @else
                                        @include('api_consumers.forms.api-consumer-reset-key-form')
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xs-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">Update API Account Email</div>
                                <div class="panel-body">
                                    @include('api_consumers.forms.update-api-consumer-form')
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="col-sm-10 col-xs-12 col-sm-offset-1 pr0 pl0">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <ul class="list-inline text-center">
                            <li class="mb10">
                                @include('api_consumers.partials._logout-button')
                            </li>
                            <li>
                                <form id="delete-api-consumer-form"
                                      method="post"
                                      action="{{action('ApiConsumerController@destroy', $api_consumer)}}"
                                      data-modal-text="API Account"
                                >
                                    @include('global.forms._delete-button', ['delete_text' => 'Delete API Account'])
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

                <hr class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr0 pl0">
            </div>
        </div>
    </div>
    @include('global.modals.delete-modal')
@endsection

@section('postscripts')
    @include('global.scripts.delete-script')
@endsection