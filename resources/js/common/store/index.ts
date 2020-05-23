import LessonsModule from "@common/store/LessonsModule";
import CoursesModule from "@common/store/CoursesModule";
import AuthModule from "@common/store/AuthModule";
import PaymentsModule from "@common/store/PaymentsModule";
import UsersModule from "@common/store/UsersModule";

export {
    LessonsModule,
    CoursesModule,
    AuthModule,
    PaymentsModule,
    UsersModule
}

import CommonStore from "@common/store/CommonStore";
import {createVuexStore, useStore} from "vuex-simple";

let store = new CommonStore();
let vuexStore = createVuexStore(store);

export function getCommonStore(): CommonStore {
    return useStore(vuexStore)
}

export {
    vuexStore
}
