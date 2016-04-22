var appGlobals = {
    csrf: $('meta[name="csrf-token"]').attr('content'),
    urlProtocol: String($('meta[name="url-protocol"]').attr('content')),
    rootAppPath: String($('meta[name="app-root"]').attr('content')),
    appDomain: String($('meta[name="app-domain"]').attr('content')),
    jwtMin: Number($('meta[name="jwt-min"]').attr('content'))
};