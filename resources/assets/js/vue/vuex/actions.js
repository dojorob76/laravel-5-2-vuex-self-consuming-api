import * as types from './mutation-types'

export const setLoggedIn = ({dispatch}, jwt) => {
    jwToken.addCookie(jwt);
    dispatch(types.AUTHORIZE, jwt);
};

export const setLoggedOut = ({dispatch}) => {
    jwToken.removeCookie();
    dispatch(types.DEAUTHORIZE);
};

export const setAuthStatus = ({dispatch}) => {
    dispatch(types.SET_STATUS);
};