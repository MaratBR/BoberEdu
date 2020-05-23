import {Module} from "vuex-simple";

import client from "@common/axios";
import {AuthModule, CoursesModule, LessonsModule, PaymentsModule, UsersModule} from "@common/store";

export default class CommonStore {
    @Module() public auth = new AuthModule(client);
    @Module() public courses = new CoursesModule(client);
    @Module() public payments = new PaymentsModule(client);
    @Module() public users = new UsersModule(client);
    @Module() public lessons = new LessonsModule(client);
}
