import axios from "axios"

import "./models"
import "./components/bootstrap"
import Vuex from 'vuex'
import Vue from 'vue'
import 'imask';

Vue.use(Vuex);

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
