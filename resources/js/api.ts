import axios from "axios"
import {EventBus} from "./bus";
import {store} from "./store";

type ErrorResponse = {
    message: string,
    errors: {
        [key: string]: string[]
    }
}

function isError<T>(v: ErrorResponse | T): v is ErrorResponse {
    let va = v as any;
    return typeof va.message === 'string' && typeof va.errors === 'object' && va.errors instanceof Array;
}

type _SuccessLoginResponse = {
    accessToken: string,
    expiresIn: number,
    tokenType: string,
    success: true
};

export type LoginResponse = _SuccessLoginResponse | {error: string, success: false}

export type LoginRequest = {
    name: string,
    password: string
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

export type RegisterResponse = ErrorResponse | {
    user: User,
    login: _SuccessLoginResponse
}

type DatetimeStr = string

type Timestamps = {
    created_at: DatetimeStr
}

export type User = {
    name: string,
    id: number
} & Timestamps

class Api {
    private user: User | null = null;
    private isReady: boolean = false;

    constructor() {
        this.prepare();
    }

    login(data: LoginRequest): Promise<LoginResponse> {
        return axios.post<LoginResponse>('/api/auth/login', data)
            .then(resp => {
                if (resp.data.success) {
                    console.log(resp.data)
                    this.setToken(resp.data.accessToken)
                }
                return resp.data
            });
    }

    getAuthentication(): string | null {
        let val = localStorage.getItem('authentication')
        val = val ? `Bearer ${val}` : null;
        return val
    }

    ready(): Promise<void> {
        if (this.isReady)
            return Promise.resolve();
        else
        {
            return new Promise(function (resolve) {
                function resolveWrapper() {
                    EventBus.$off('api-ready', resolveWrapper);
                    resolve();
                }

                EventBus.$on('api-ready', resolveWrapper);
            });
        }
    }

    private setToken(token: string): void {
        localStorage['__tok'] = token;
        Api.updateToken();
        this.syncUserFromServer();
    }

    private static updateToken(): void {
        axios.defaults.headers['Authorization'] = localStorage['__tok'] ? 'Bearer ' + localStorage['__tok'] : null;
    }

    private static getToken(): string | null {
        return localStorage['__tok']
    }

    register(data: RegisterRequest) {
        return axios.post<RegisterResponse>('/api/auth/register', data)
            .then((resp) => {
                if (!isError(resp.data)) {
                    this.setToken(resp.data.login.accessToken)
                    Api.setUser(resp.data.user)

                }
                console.log(resp.data)
                return resp.data
            });
    }

    private static setUser(user: User): void {
        store.commit('setUser', user);
    }

    private static retrieveUser(): Promise<User | null> {
        return new Promise<User | null>(function (resolve) {
            axios.get<User>('/api/auth/user')
                .then(r => resolve(r.data))
                .catch(err => resolve(null))
        })
    }

    syncUserFromServer(silent: boolean = false): Promise<User | null> {
        if (!silent)
            store.commit('setUserSyncing', true);
        return Api.retrieveUser()
            .then((user: User | null) => {
                if (!silent)
                    store.commit('setUserSyncing', false);
                Api.setUser(user)
                return user;
            });
    }

    getUser(): User | null {
        return store.state.user
    }

    private prepare() {
        Api.updateToken();
        Promise.all([
            this.syncUserFromServer() as Promise<any>
        ]).then(r => EventBus.$emit('api-ready'));
    }

    getUserById(id: number): Promise<User> {
        return axios
            .get<User>('/api/users/' + id)
            .then(r => r.data)
    }
}

export const api = new Api();
