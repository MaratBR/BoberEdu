import "./bootstrap"
import Vue from "vue"
import Router from "vue-router"
import router from "./router";

import App from "./App.vue"
import {Store, store} from "./store";
import {ValidationProvider} from "vee-validate";
import {useStore} from "vuex-simple";


Vue.use(Router);
Vue.component('validation-provider', ValidationProvider);


const app = new Vue({
    el: '#app',
    render: h => h(App),
    router,
    store,
    async created() {
        let store = useStore<Store>(this.$store);
        await store.auth.init()
    }
});


