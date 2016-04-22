<!-- App Meta Content For Scripts -->
<meta name="app-domain" id="app-domain" content="{{env('SESSION_DOMAIN')}}">
<meta name="app-root" id="app-root" content="{{env('APP_MAIN')}}">
<meta name="url-protocol" id="url-protocol" content="{{env('URL_PROTOCOL')}}">
<meta name="jwt-min" id="jwt-min" content="{{config('jwt.ttl', 180)}}">