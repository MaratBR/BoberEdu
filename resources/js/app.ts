import "./bootstrap"
import Vue from "vue"
import Router from "vue-router"
import router from "./router";

import App from "./App.vue"
import {store} from "./store";
import {ValidationProvider} from "vee-validate";
import "./validationRules"
import * as Cleave from 'vue-cleave-component';

Vue.use(Cleave);
Vue.use(Router);
Vue.component('validation-provider', ValidationProvider);


const app = new Vue({
    el: '#app',
    render: h => h(App),
    router,
    store
});


