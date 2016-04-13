var appGlobals = {
    csrf: $('meta[name="csrf-token"]').attr('content'),
    appDomain: $('meta[name="session-domain"]').attr('content'),
    rootAppPath: $('meta[name="app-main"]').attr('content'),
    urlProtocol: $('meta[name="url-protocol"]').attr('content')
};