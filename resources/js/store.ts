import Vuex from 'vuex'
import Vue from 'vue'
import createPersistedState from "vuex-persistedstate";
import * as SecureLS from "secure-ls";
import {IUser} from "./models/user";
const ls = new SecureLS({ isCompression: false });

type State = {
    user: IUser | null | undefined,
    userSyncing: boolean,
    authToken?: string | null
}
Vue.use(Vuex);

const store = new Vuex.Store<State>({
    state: {
        user: null,
        userSyncing: false
    },
    mutations: {
        setUser(state: State, user: IUser | null): void {
            state.user = user
        },
        setUserSyncing(state: State, v: boolean): void {
            state.userSyncing = v
        },
        setToken(state: State, token: string = null): void {
            state.authToken = token
        }
    },
    getters: {
        username(state: State): string | null {
            return state.user ? state.user.name : null;
        },
        isAuthenticated(state: State): boolean {
            return !!state.user
        }
    },
    plugins: [
        createPersistedState({
            storage: {
                getItem: key => ls.get(key),
                setItem: (key, value) => ls.set(key, value),
                removeItem: key => ls.remove(key)
            }
        })
    ]
});

export {store};
