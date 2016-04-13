@if(isset($errors) && count($errors) > 0)
    <div class="alert alert-danger">
        <p class="text-center">
            <span class="bold">Security Error:</span> The API Access Token has been compromised and was not activated.
            @foreach ($errors->all() as $error)
            {!! $error !!},
            @endforeach
        </p>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-center">
            Unfortunately, an error was encountered during the attempt to activate your new API Access Token.<br>Please
            re-enter your valid email address in the field below to try again.
        </p>
    </div>
    <hr class="col-sm-10 col-xs-12 col-sm-offset-1 pr0 pl0">
@else
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        @include('api_consumers.shared.partials._activate-page-else')
    </div>
@endif