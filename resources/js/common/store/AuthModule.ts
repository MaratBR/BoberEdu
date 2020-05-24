import StoreModuleBase from "./StoreModuleBase";
import {Action, Getter, Mutation, State} from "vuex-simple";
import {dto, requests} from "@common";
import UserDto = dto.UserDto;


export default class AuthModule extends StoreModuleBase {
    @State() user: UserDto | null = null;
    @State() accessToken: string | null = null;
    @State() loggingIn: boolean = false;

    @Mutation() SET_LOGGING_IN(val: boolean) {
        this.loggingIn = val;
    }

    @Mutation() SET_USER(user: UserDto | null) {
        this.user = user;
        console.log(user)
    }

    @Getter() get isAuthenticated(): boolean {
        return !!this.user
    }

    @Getter() get isAdmin(): boolean {
        return this.user && this.user.roles.indexOf('admin') !== -1
    }

    @Action()
    async init() {
        await this.fetchCurrentUser();
    }

    @Action()
    async fetchCurrentUser(): Promise<void> {
        try {
            let {data} = await this.client.get<UserDto>('auth/user');
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
            await this.client.post('auth/login', request);
            await this.fetchCurrentUser();
        } finally {
            this.SET_LOGGING_IN(false)
        }
    }

    @Action()
    async register(request: requests.Register) {
        if (this.isAuthenticated)
            throw new Error('You are already logged in! You can\'t register a new account, log out first please');

        await this.client.post('auth/register', request);
    }

    @Action()
    async logout() {
        this.clearSession()
    }

    @Action()
    clearSession() {
        this.SET_USER(null);
    }
}
