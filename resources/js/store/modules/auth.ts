import {Store} from 'vuex'
import {IUser} from "../../models/user";
import {api, RegisterRequest} from "../../api";
import {reportError} from "../../utils";

export type AuthState = {
    user: IUser | null,
    authToken?: string,
    lastLoginAttempt?: string,
    lastSuccessFullLoginAttempt?: string,
    syncing: boolean,
    loggingIn: boolean
}

export default {
    state: {
        syncing: false,
        loggingIn: false,
        user: null
    },
    namespaced: true,
    mutations: {
        SET_USER(state: AuthState, user: IUser | null) {
            state.user = user
        },

        SET_USER_SYNCING(state: AuthState, value: boolean) {
            state.syncing = value
        },

        SET_LAST_LOGIN_ATTEMPT(state: AuthState, attempt: string) {
            state.lastLoginAttempt = attempt
        },

        SET_LAST_SUCCESSFUL_LOGIN_ATTEMPT(state: AuthState, attempt: string) {
            state.lastSuccessFullLoginAttempt = attempt
        },

        SET_AUTH_TOKEN(state: AuthState, token: string) {
            state.authToken = token
        },

        SET_LOGGING_IN(state: AuthState, value: boolean) {
            state.loggingIn = value
        }
    },
    actions: {
        attemptLogin({commit}, {name, password}) {
            commit('SET_LAST_LOGIN_ATTEMPT', name);
            commit('SET_LOGGING_IN', true);
            return api.auth.login({name, password})
                .then(response => {
                    api.auth.setAuthorization(response.accessToken);
                    commit('SET_AUTH_TOKEN', response.accessToken);
                    commit('SET_LAST_SUCCESSFUL_LOGIN_ATTEMPT', name);
                })
                .finally(() => commit('SET_LOGGING_IN', false))
        },
        logout() {
            // TODO
        },
        register({commit}, data: RegisterRequest) {
            return api.auth.register(data)
                .then(r => {
                    commit('SET_USER', r.user);
                    commit('SET_AUTH_TOKEN', r.login.accessToken);
                })
        },
        updateUser({commit}): Promise<IUser | null> {
            commit('SET_USER_SYNCING', true);
            return api.auth.syncUserFromServer()
                .then(user => commit('SET_USER', user))
                .finally(() => commit('SET_USER_SYNCING', false))
        }
    },
    getters: {
        isAuthenticated: state => state.user !== null
    }
}
