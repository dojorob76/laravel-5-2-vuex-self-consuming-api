var successCb = {
    setAuthorized: function (data, textStatus, jqXHR) {
        if (data.jwtoken) {
            var jwt = data.jwtoken;
            if (jwt != null && jwt != 'undefined') {
                jwToken.addCookie(jwt);
            }
        }
    },
    unsetAuthorized: function (data, textStatus, jqXHR) {
        //
    }
};