@extends('app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h2 class="page-header">{{$page_title}}</h2>

                <!-- Display Flash Messages -->
                @include('global.partials._flash-messages')

                @if(session()->has('access_token') && session()->has('access_id'))
                    @include('api_consumers.shared.partials._activate-page-if')

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        @include('api_consumers.admin.forms.admin-activate-api-consumer-form')
                    </div>
                @else
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        @include('api_consumers.shared.partials._activate-page-else')

                        @include('api_consumers.admin.forms.admin-reactivate-api-consumer-form')
                    </div>
                @endif

                <hr class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-right: 0; padding-left: 0;">
            </div>
        </div>
    </div>
@endsection

@section('postscripts')
    @include('global.scripts.clipboard-script')
@endsection