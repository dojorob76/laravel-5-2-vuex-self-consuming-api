var jwToken = {
    getFromCookie: function () {
        // If there is a JWT cooke, return it, otherwise return null
        return docCookies.hasItem('jwt') ? docCookies.getItem('jwt') : null;
    },
    getFromHeader: function (jqXHR) {
        // Get the value of Authorization from a Response header
        var fullToken = jqXHR.getResponseHeader('Authorization');
        // Remove 'Bearer ' to set jwtoken to the JWT value only
        return fullToken != null ? fullToken.substr(fullToken.indexOf(' ') + 1) : null;
    },
    setHeaderFromCookie: function () {
        // If the jwt cookie exists, set the Authorization header to 'Bearer jwtvalue'
        var jwt = this.getFromCookie();
        return jwt != null ? 'Bearer ' + jwt : null;
    },
    setCookieFromHeader: function (jqXHR) {
        var jwt = this.getFromHeader(jqXHR);
        if (jwt != null) {
            this.addCookie(jwt);
        }
    },
    addCookie: function (jwt) {
        var ttl = appGlobals.jwtMin * 60;
        // Set the JWT in the jwt cookie - valid for the JWT TTL (in seconds) on the session domain
        docCookies.setItem('jwt', jwt, ttl, '/', appGlobals.appDomain);
    },
    removeCookie: function () {
        // Remove the jwt cookie by setting the value to null and expiring it
        docCookies.setItem('jwt', null, -2628000, '/', appGlobals.appDomain);
    }
};