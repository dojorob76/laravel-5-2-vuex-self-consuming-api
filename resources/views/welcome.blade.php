@extends('layouts.web.web-layout')

@section('web-content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome</div>

                <div class="panel-body">
                    Your Application's Landing Page.
                    <div v-if="authorized">
                        <h4 class="text-center">You are logged in</h4>
                    </div>
                    <div v-else>
                        <p class="text-center">
                            <a href="{{action('AuthenticationController@getAuthenticate')}}"
                               class="btn btn-default"
                            >
                                Sign In
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
