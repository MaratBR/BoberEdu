import Vuex from 'vuex'
import Vue from 'vue'
import 'imask';
import Router from "vue-router";
import client from "@common/axios";

Vue.use(Vuex);
Vue.use(Router);
Vue.component('star-rating', require("vue-star-rating").default);

client.defaults.headers['X-Requested-With'] = 'XMLHttpRequest';
client.defaults.withCredentials = true

