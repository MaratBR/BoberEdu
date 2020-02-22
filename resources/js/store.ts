import Vuex from 'vuex'
import Vue from 'vue'
import {User} from "./api";

type State = {
    user: User | null | undefined,
    userSyncing: boolean
}
Vue.use(Vuex);
const store = new Vuex.Store<State>({
    state: {
        user: null,
        userSyncing: false
    },
    mutations: {
        setUser(state: State, user: User | null): void {
            state.user = user
        },
        setUserSyncing(state: State, v: boolean): void {
            state.userSyncing = v
        }
    },
    getters: {
        username(state: State): string | null {
            return state.user ? state.user.name : null;
        },
        isAuthenticated(state: State): boolean {
            return !!state.user
        }
    }
});

export {store};
