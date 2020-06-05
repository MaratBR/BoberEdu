import Vuex from 'vuex'
import Vue from 'vue'
import Router from "vue-router";
import client from "@common/axios";
import VueCurrencyInput from 'vue-currency-input'
import Editor from 'v-markdown-editor'

Vue.use(Vuex);
Vue.use(VueCurrencyInput, {
    globalOptions: {
        currency: 'USD',
        locale: 'en'
    }
})

Vue.use(Router);
Vue.use(Editor);
Vue.component('star-rating', require("vue-star-rating").default);
Vue.component('masked-input', require("vue-masked-input").default);
Vue.component('scrollbar', require("vue-custom-scrollbar"));

client.defaults.headers['X-Requested-With'] = 'XMLHttpRequest';
client.defaults.withCredentials = true

