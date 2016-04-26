export function getJwt(state) {
    return state.auth.jwt
}

export function getAuthorized(state) {
    return state.auth.authorized
}