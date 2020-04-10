import StoreModuleBase from "./StoreModuleBase";
import {Action, Getter, Mutation, State} from "vuex-simple";

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
    @State() user: User | null = null;
    @State() accessToken: string | null = null;
    @State() loggingIn: boolean = false;

    @Mutation() SET_LOGGING_IN(val: boolean) {
        this.loggingIn = val;
    }

    @Mutation() SET_USER(user: User | null) {
        this.user = user;
    }

    @Mutation() SET_TOKEN(token: string | null) {
        this.accessToken = token;
        this.client.defaults.headers.Authorization = 'Bearer ' + token;
    }

    @Getter() get isAuthenticated(): boolean {
        return !!this.user
    }

    @Action()
    async fetchCurrentUser(): Promise<void> {
        let {data} = await this.client.get<User>('auth/user');
        this.SET_USER(data)
    }

    @Action()
    async login(request: LoginRequest): Promise<void> {
        try
        {
            if (this.isAuthenticated)
                throw new Error('You are already logged in!');
            this.SET_LOGGING_IN(true);
            let {data} = await this.client.post<LoginResponse>('auth/login', request);
            this.SET_TOKEN(data.token);
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
        this.SET_TOKEN(null);
        this.SET_USER(null);
    }
}
