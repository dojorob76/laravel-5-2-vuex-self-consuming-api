@extends('app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h2 class="page-header">{{$page_title}}</h2>

                <!-- Display Flash Messages -->
                @include('global.partials._flash-messages')

                @if($api_consumer)
                    @include('api_consumers.partials._activation-reset-key-msg')
                @else
                    @include('api_consumers.shared.partials._reactivate-page')

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        @include('api_consumers.forms.reactivate-api-consumer-form')
                    </div>
                @endif

                <hr class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr0 pl0">
            </div>
        </div>
    </div>
@endsection