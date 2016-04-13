@extends('app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h2 class="page-header">{{$page_title}}</h2>

                <!-- Display Flash Messages -->
                @include('global.partials._flash-messages')

                @include('api_consumers.shared.partials._reactivate-page')

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    @include('api_consumers.admin.forms.admin-reactivate-api-consumer-form')
                </div>

                <hr class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-right: 0; padding-left: 0;">
            </div>
        </div>
    </div>
@endsection