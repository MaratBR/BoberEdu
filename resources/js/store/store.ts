import Vuex from 'vuex'
import Vue from 'vue'

Vue.use(Vuex);

import createPersistedState from "vuex-persistedstate";
import * as SecureLS from "secure-ls";

import auth, {AuthState} from "./modules/auth"
import courses, {CoursesState} from "./modules/courses"
import {api} from "../api";

const ls = new SecureLS({ isCompression: false });

type State = {
    auth?: AuthState,
    courses?: CoursesState
}

const store = new Vuex.Store<State>({
    state: {
    },
    mutations: {
    },
    getters: {
    },
    actions: {
    },
    plugins: [
        createPersistedState({
            storage: {
                getItem: key => ls.get(key),
                setItem: (key, value) => ls.set(key, value),
                removeItem: key => ls.remove(key),
            },
            paths: ['auth']
        })
    ],
    modules: {
        auth,
        courses
    }
});

export {
    store
};
