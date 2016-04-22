@extends('layouts.admin.admin-layout')

@section('admin-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h2 class="page-header">{{$page_title}}</h2>
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel panel-default">
                        <div class="panel-heading">Welcome</div>

                        <div class="panel-body">
                            Your Administration Subdomain Dashboard.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection