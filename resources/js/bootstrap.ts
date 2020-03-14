import axios from "axios"

import "./validationRules"
import "./models"
import "loglevel"
import "./api"
import "./components/bootstrap"

import Vuex from 'vuex'
import Vue from 'vue'

Vue.use(Vuex);

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
