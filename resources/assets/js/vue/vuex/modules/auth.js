import {AUTHORIZE, DEAUTHORIZE, SET_STATUS} from '../mutation-types'

// Initial State
const state = {
    jwt: null,
    authorized: false
};

// Mutations
const mutations = {
    [AUTHORIZE](state, jwt){
        state.authorized = true;
        state.jwt = jwt;
    },
    [DEAUTHORIZE](state){
        state.authorized = false;
        state.jwt = null;
    },
    [SET_STATUS](state){
        state.jwt = jwToken.getFromCookie();
        state.authorized = state.jwt != null;
    }
};

export default{
    state,
    mutations
}
