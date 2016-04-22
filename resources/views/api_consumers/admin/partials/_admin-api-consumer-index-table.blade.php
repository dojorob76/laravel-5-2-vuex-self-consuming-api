<table class="table table-striped">
    <thead>
    <tr>
        <th><span class="bold">ID</span></th>
        <th><span class="bold">Email</span></th>
        <th><span class="bold">Level</span></th>
        <th class="text-center"><span class="bold">Actions</span></th>
    </tr>
    </thead>
    <tbody>
    @foreach($api_consumers as $api_consumer)
        @unless($api_consumer->isSystemAccount() && $bouncer->denies('view-system-api-accounts', $api_consumer))
            <tr
                @if($api_consumer->isSystemAccount())
                    class="danger"
                @elseif($api_consumer->level === 0)
                    class="warning"
                @endif
            >
                <td>{{$api_consumer->id}}</td>
                <td>{{$api_consumer->email}}</td>
                <td>{{$api_consumer->level}}</td>
                <td>
                    <ul class="list-inline text-center">
                        @unless($api_consumer->isSystemAccount() && $bouncer->denies('edit-system-api-accounts', $api_consumer))
                            <li class="mb10 mr5">
                                <a class="btn btn-default"
                                   href="{{action('Admin\AdminApiConsumerController@show', $api_consumer->id)}}"
                                > View/Edit </a>
                            </li>
                        @endunless
                        @unless($api_consumer->isSystemAccount() && $bouncer->denies('delete-system-api-accounts', $api_consumer))
                            <li class="ml5">
                                <form id="admin-delete-api-consumer-{{$api_consumer->id}}-form"
                                      method="post"
                                      action="{{action('Admin\AdminApiConsumerController@destroy', $api_consumer)}}"
                                      data-modal-text="API Account"
                                >
                                    @include('global.forms._delete-button')
                                </form>
                            </li>
                        @endunless
                    </ul>
                </td>
            </tr>
        @endunless
    @endforeach
    </tbody>
</table>