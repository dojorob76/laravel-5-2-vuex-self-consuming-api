@extends('layouts.admin.admin-layout')

@inject('bouncer', 'Silber\Bouncer\Bouncer')

@section('admin-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h2 class="page-header">{{$page_title}}</h2>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p class="text-center">
                        {{$site_name}} currently has <span class="bold">{{count($api_consumers)}}</span> API Consumers.
                    </p>

                    <h4 class="page-header">Create New API Consumer</h4>
                    <p class="text-center">
                        Use the button below to create a new API Consumer as an administrator.
                    </p>
                    <div class="text-center">
                        <a class="btn btn-info" href="{{action('Admin\AdminApiConsumerController@create')}}">
                            Create New API Consumer
                        </a>
                    </div>

                    <h4 class="plain page-header">Manage Existing API Consumers</h4>
                    <p class="text-center">
                        Below is the full list of API Consumers currently registered to use the {{$site_name}} API.<br>
                        Use the buttons in the table rows to View, Edit, or Delete an existing API Consumer.
                    </p>

                    <hr class="col-sm-10 col-xs-12 col-sm-offset-1 pr0 pl0">

                    @include('api_consumers.admin.partials._admin-api-consumer-index-table')
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