import "@app/bootstrap"

import Vue from "vue";
import router from "@app/router";
import {vuexStore} from "@common/store";

let app = new Vue({
    render: h => h('router-view'),
    router,
    store: vuexStore
})

export default app;
