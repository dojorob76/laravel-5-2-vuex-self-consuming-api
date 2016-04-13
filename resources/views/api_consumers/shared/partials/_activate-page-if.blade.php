<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <p class="text-center">
        <span class="bold dkred">IMPORTANT:</span> <em>This token will only be displayed once!</em>
    </p>
    <p class="text-center">
        Please copy it now, and store it in a safe place. Once you have successfully copied and stored the token, click
        the 'Activate' button below to start using it.
    </p>
    <form class="form-inline text-center">
        <div class="form-group">
            <label>Your API Access Token:</label>
            <input class="form-control"
                   id="access-token-once"
                   value="{{session('access_token')}}"
                   style="min-width: 200px;"
                   readonly
            >
            <a class="btn btn-primary clipboard" data-clipboard-target="#access-token-once">
                <i class="fa fa-clipboard"></i><span class="sr-only">Copy token to clipboard</span>
            </a>
            <label class="label label-info clipboard-result"></label>
        </div>
    </form>
    <hr class="col-sm-10 col-xs-12 col-sm-offset-1 pr0 pl0">
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <p class="text-center">
        Do not activate until token is safely stored. It will not be displayed again.
    </p>
</div>