@extends('layouts.admin.admin-layout')

@section('admin-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h2 class="page-header">{{$page_title}}</h2>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p class="text-center">
                        To create a new API Consumer, please enter a valid email address in the form below.
                    </p>
                </div>

                <div class="col-sm-8 col-sm-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">Create New API Consumer</div>
                        <div class="panel-body">
                            @include('api_consumers.admin.forms.admin-create-api-consumer-form')
                        </div>
                    </div>
                </div>

                <hr class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr0 pl0">
            </div>
        </div>
    </div>
@endsection