import {Action, Getter, Module, Mutation, State} from "vuex-simple";

import client from "@common/axios";
import {AuthModule, CoursesModule, LessonsModule, PaymentsModule, UsersModule} from "@common/store";
import {dto, requests} from "@common";
import {randomId} from "@common/utils";

export default class CommonStore {
    @Module() public courses = new CoursesModule(client);
    @Module() public payments = new PaymentsModule(client);
    @Module() public users = new UsersModule(client);
    @Module() public lessons = new LessonsModule(client);

    //#region Auth

    @State() user: dto.UserDto | null = null;
    @State() logoutToken: string = null;
    @State() loggingIn: boolean = false;

    @Mutation() SET_LOGGING_IN(val: boolean) {
        this.loggingIn = val;
    }

    @Mutation() SET_USER(user: dto.UserDto | null) {
        this.user = user;
        this.logoutToken = localStorage.getItem('logoutToken')
        if (user === null) {
            localStorage.setItem('logoutToken', null)
        } else {
            if (!this.logoutToken) {
                this.logoutToken = randomId(8)
                localStorage.setItem('logoutToken', this.logoutToken)
            }
        }
    }

    @Getter() get isAuthenticated(): boolean {
        return !!this.user
    }

    @Getter() get isAdmin(): boolean {
        return this.user && this.user.admin
    }

    @Action()
    async init() {
        await this.fetchCurrentUser();
    }

    @Action()
    async fetchCurrentUser(): Promise<void> {
        try {
            let {data} = await client.get<dto.UserDto>('auth/user');
            this.SET_USER(data)
        } catch (e) {
            this.clearSession()
        }
    }

    @Action()
    async login(request: requests.Login): Promise<void> {
        try {
            if (this.isAuthenticated)
                throw new Error('You are already logged in!');
            this.SET_LOGGING_IN(true);
            await client.post('auth/login', request);
            await this.fetchCurrentUser();
        } finally {
            this.SET_LOGGING_IN(false)
        }
    }

    @Action()
    async register(request: requests.Register) {
        if (this.isAuthenticated)
            throw new Error('You are already logged in! You can\'t register a new account, log out first please');

        await client.post('auth/register', request);
    }

    @Action()
    async logout() {
        await client.post('auth/logout')
        this.clearSession()
    }

    @Action()
    clearSession() {
        this.SET_USER(null);
    }

    //#endregion

    //#region Teachers

    getTeacher(id: number): Promise<dto.TeacherProfileDto> {
        return client.get('teachers/' + id).then(r => r.data)
    }

    //#endregion

}
