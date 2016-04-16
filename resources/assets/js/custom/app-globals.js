var appGlobals = {
    csrf: $('meta[name="csrf-token"]').attr('content'),
    appDomain: $('#app-data').data('app-domain'),
    rootAppPath: $('#app-data').data('app-main'),
    urlProtocol: $('#app-data').data('url-protocol')
};