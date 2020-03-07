import "./bootstrap"
import Vue from "vue"
import Router from "vue-router"
import router from "./router";

import App from "./App.vue"
import {store} from "./store";
import {ValidationProvider} from "vee-validate";
import "./validationRules"

Vue.use(Router);
Vue.component('validation-provider', ValidationProvider);


const app = new Vue({
    el: '#app',
    render: h => h(App),
    router,
    store
});


