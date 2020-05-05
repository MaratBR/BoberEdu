import StoreModuleBase from "./StoreModuleBase";
import {Action, Getter, Mutation, State} from "vuex-simple";
import {dto} from "../dto";
import UserDto = dto.UserDto;

export type User = {
    id: number,
    name: string
}

export type LoginRequest = {
    name: string,
    password: string
}

export type LoginResponse = {
    token: string,
    user_id: number
}

export enum Sex {
    Unknown = 'u',
    Male = 'm',
    Female = 'f'
}

export const sexes: {[key: string]: string} = {
    [Sex.Unknown]: 'Unknown',
    [Sex.Female]: 'Female',
    [Sex.Male]: 'Male'
};

export type RegisterRequest = {
    name: string,
    password: string,
    email: string,
    sex?: Sex
}

export class AuthModule extends StoreModuleBase {
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

    @Mutation() SET_ACCESS_TOKEN(token: string | null) {
        this.accessToken = token;
        this.setAuthorizationHeader();
    }

    private setAuthorizationHeader() {
        this.client.defaults.headers.Authorization = 'Bearer ' + this.accessToken;
    }

    @Getter() get isAuthenticated(): boolean {
        return !!this.user
    }

    @Getter() get isAdmin(): boolean {
        return this.user && this.user.roles.includes('admin')
    }

    @Action()
    async init() {
        this.setAuthorizationHeader();
        await this.fetchCurrentUser();
    }

    @Action()
    async fetchCurrentUser(): Promise<void> {
        try
        {
            let {data} = await this.client.get<UserDto>('auth/user');
            this.SET_USER(data)
        }
        catch (e) {
            this.clearSession()
        }
    }

    @Action()
    async login(request: LoginRequest): Promise<void> {
        try
        {
            if (this.isAuthenticated)
                throw new Error('You are already logged in!');
            this.SET_LOGGING_IN(true);
            let {data} = await this.client.post<LoginResponse>('auth/login', request);
            this.SET_ACCESS_TOKEN(data.token);
            await this.fetchCurrentUser();
        }
        finally
        {
            this.SET_LOGGING_IN(false)
        }
    }

    @Action()
    async register(request: RegisterRequest) {
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
        this.SET_ACCESS_TOKEN(null);
        this.SET_USER(null);
    }
}
