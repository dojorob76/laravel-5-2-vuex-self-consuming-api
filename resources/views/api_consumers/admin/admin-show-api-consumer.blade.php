@extends('layouts.admin.admin-layout')

@inject('bouncer', 'Silber\Bouncer\Bouncer')

@section('admin-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h2 class="page-header">{{$page_title}}</h2>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p class="text-center">
                        This is the <strong>ADMIN</strong> Settings page for API Consumer {{$api_consumer->id}}.
                    </p>
                    @if($api_consumer->level === 0)
                        <div class="alert alert-warning">
                            <p class="text-center">
                                This API Consumer has not yet activated their API Access Token.
                            </p>
                        </div>
                    @endif
                    <hr>
                    @if($api_consumer->isSystemAccount() && $bouncer->allows('edit-system-api-accounts', $api_consumer))
                        <div class="alert alert-info text-center">
                            <span class="bold">IMPORTANT ADMIN NOTICE:</span><br> <u>This is a system API account.</u>
                            Any changes made to these settings must also be updated in the system environment
                            variables!!
                        </div>
                    @endif
                    @unless($api_consumer->isSystemAccount() && $bouncer->denies('edit-system-api-accounts', $api_consumer))
                        @include('global.forms._form-errors')
                        <div class="col-sm-6 col-xs-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">Refresh API Consumer Access Token</div>
                                <div class="panel-body">
                                    @if($api_consumer->reset_key != null)
                                        @include('api_consumers.admin.forms.admin-api-consumer-refresh-token-form')
                                        <hr>
                                        @include('api_consumers.admin.forms.admin-api-consumer-resend-reset-key-form')
                                    @else
                                        @include('api_consumers.admin.forms.admin-api-consumer-reset-key-form')
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">Update API Consumer Settings</div>
                                <div class="panel-body">
                                    @include('api_consumers.admin.forms.admin-update-api-consumer-form')
                                </div>
                            </div>
                        </div>
                    @endunless
                </div>

                @unless($api_consumer->isSystemAccount() && $bouncer->denies('delete-system-api-accounts', $api_consumer))
                    <hr class="col-sm-10 col-xs-12 col-sm-offset-1 pr0 pl0">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        @if($api_consumer->isSystemAccount())
                            <div class="alert alert-danger">
                                <p class="text-center bold">WARNING!!</p>

                                <p class="text-center">
                                    <u>This is a System API Account.</u> <strong>Deleting it can BREAK YOUR
                                                                                 SITE!</strong><br> Please DO NOT DELETE
                                                                                                    unless you are <em>absolutely
                                                                                                                       certain</em>
                                                                                                    that your code no
                                                                                                    longer depends on
                                                                                                    it.
                                </p>
                            </div>
                        @endif
                        <form id="admin-delete-api-consumer-form"
                              method="post"
                              action="{{action('Admin\AdminApiConsumerController@destroy', $api_consumer)}}"
                              data-modal-text="API Account"
                        >
                            @include('global.forms._delete-button', ['delete_text' => 'Delete API Consumer Account'])
                        </form>
                    </div>
                @endunless

                <hr class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr0 pl0">
            </div>
        </div>
    </div>
    @unless($api_consumer->isSystemAccount() && $bouncer->denies('delete-system-api-accounts', $api_consumer))
        @include('global.modals.delete-modal')
    @endunless
@endsection

@section('postscripts')
    @include('global.scripts.delete-script')
@endsection