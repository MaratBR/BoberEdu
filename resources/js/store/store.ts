import Vuex from 'vuex'
import Vue from 'vue'

Vue.use(Vuex);

import * as SecureLS from "secure-ls";
import axios from 'axios'
import {AuthModule} from "./modules/AuthModule"
import {createVuexStore, Module} from "vuex-simple";
import CoursesModule from "./modules/CoursesModule";
import createPersistedState from "vuex-persistedstate";
import PurchaseModule from "./modules/PurchaseModule";

const ls = new SecureLS({ isCompression: false });

const client = axios.create();
client.defaults.baseURL = location.origin + '/api';

export class Store {
    @Module() public auth = new AuthModule(client);
    @Module() public courses = new CoursesModule(client);
    @Module() public purchases = new PurchaseModule(client);
}

const store = createVuexStore(new Store(), {
    plugins: [
        createPersistedState({
            getState: key => ls.get(key),
            setState: (key, value) => ls.set(key, value),
            paths: ['auth']
        })
    ]
});

export {
    store
};
