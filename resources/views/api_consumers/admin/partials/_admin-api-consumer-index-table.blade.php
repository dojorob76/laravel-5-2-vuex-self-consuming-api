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
            <tr @if($api_consumer->level === 0) class="danger" @endif>
                <td>{{$api_consumer->id}}</td>
                <td>
                    {{$api_consumer->email}}
                </td>
                <td>
                    {{$api_consumer->level}}
                </td>
                <td>
                    <ul class="list-inline text-center">
                        <li style="margin-bottom: 10px; margin-right: 5px;">
                            <a class="btn btn-default"
                               href="{{action('Admin\AdminApiConsumerController@show', $api_consumer->id)}}"
                            >
                                View/Edit
                            </a>
                        </li>
                        <li style="margin-left: 5px;">
                            <a class="btn btn-danger"
                               href="{{action('Admin\AdminApiConsumerController@destroy', $api_consumer->id)}}"
                            >
                                Delete
                            </a>
                        </li>
                    </ul>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>