<ul class="list-inline text-center">
    <li class="mb10">
        <a class="btn btn-primary add-feedback"
           href="{{action('ApiConsumerController@show', $api_consumer->id)}}"
        >
            @include('global.partials._button-wait')
            <span class="button-text">Manage My API Account</span>
        </a>
    </li>
    <li>
        @include('api_consumers.partials._logout-button')
    </li>
</ul>