
import "@app/bootstrap"

import Vue from "vue";
import router from "@app/router";
import {getCommonStore, vuexStore} from "@common/store";

export function createApp() {
    return new Vue({
        render: h => h('router-view'),
        router,
        store: vuexStore
    })
}
export function init(): Promise<void> {
    return getCommonStore().auth.init()
}

init().then(() => {
    createApp().$mount('#app')
})
