module.exports = {
    jwt: null,
    authorized: false,

    setAuthStatus: function () {
        this.verifyToken(this.jwt);
    },

    setLoggedIn: function (jwt) {
        jwToken.addCookie(jwt);
        this.jwt = jwt;
        this.authorized = true;
    },

    setLoggedOut: function () {
        jwToken.removeCookie();
        this.jwt = null;
        this.authorized = false;
    },

    verifyToken: function (jwt = null) {
        var path = appGlobals.urlProtocol + appGlobals.rootAppPath + '/verify-token';
        if (jwt != null) {
            path += '?token=' + jwt;
        }
        var self = this;
        $.get(path)
            .done(function (data) {
                self.getStatusFromResponse(data);
            })
            .fail(function () {
                self.setLoggedOut();
            });
    },

    getStatusFromResponse(data){
        var self = this;
        if (data.jwtoken) {
            var jwt = data.jwtoken;
            if (jwt != null && jwt != 'undefined') {
                self.setLoggedIn(jwt);
            }
            else {
                self.setLoggedOut();
            }
        }
        else {
            self.setLoggedOut();
        }
    }
};