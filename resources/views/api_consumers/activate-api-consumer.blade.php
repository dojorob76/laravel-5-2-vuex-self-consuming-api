@extends('layouts.web.web-layout')

@section('web-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h2 class="page-header">{{$page_title}}</h2>

                @if(session()->has('access_token') && session()->has('access_id'))
                    @include('api_consumers.shared.partials._activate-page-if')
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        @include('api_consumers.forms.activate-api-consumer-form')
                    </div>
                @else
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        @if($api_consumer)
                            @include('api_consumers.partials._activation-reset-key-msg')
                        @else
                            @include('api_consumers.shared.partials._activate-page-else')
                            @include('api_consumers.forms.reactivate-api-consumer-form')
                        @endif
                    </div>
                @endif

                <hr class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr0 pl0">
            </div>
        </div>
    </div>
@endsection

@section('postscripts')
    @include('global.scripts.clipboard-script')
@endsection