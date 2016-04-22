<p>Greetings,</p>

<p>You are receiving this email because you have requested a new {{$site_name}} API Token.</p>

<p>
    To refresh your token, simply copy and paste the 'Reset Key' below into the Token Refresh form on
    <a href={{action('ApiConsumerController@show', $api_consumer->id)}}>your API Settings page</a>.
</p>

<p><strong>Your Reset Key:</strong> <code>{{$api_consumer->reset_key}}</code></p>

<p>
    If you did not make this request, your API Account may be compromised, so you should refresh your API Token
    immediately.
</p>

<p>Regards,<br>The {{$site_name}} Team</p>