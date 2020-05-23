import {Module, registerModule, useModule} from "vuex-simple";
import {vuexStore} from "@common/store";
import CoursesAdminModule from "@admin/store/CoursesAdminModule";
import client from "@common/axios";

export class AdminModule {
    @Module() courses = new CoursesAdminModule(client);
}

registerModule(vuexStore, ['dyn_admin'], new AdminModule());

export function getAdminModule(): AdminModule {
    return useModule(vuexStore, ['dyn_admin'])
}
