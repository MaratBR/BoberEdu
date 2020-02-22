import "./bootstrap"
import Vue from "vue"
import Router from "vue-router"
import router from "./router";

import App from "./App.vue"
import {store} from "./store";
import {ValidationProvider} from "vee-validate";
import DatePick from 'vue-date-pick/src/vueDatePick.vue';


import "./validationRules"

Vue.use(Router);
Vue.component('vue-date-pick', DatePick);
Vue.component('validation-provider', ValidationProvider);

const app = new Vue({
    el: '#app',
    render: h => h(App),
    router,
    store,
    components: {
        'vue-date-pick': DatePick
    }
});

