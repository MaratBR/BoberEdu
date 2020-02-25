import axios, {AxiosResponse} from "axios"
import {EventBus} from "./bus";
import {store} from "./store";
import {Course, CoursePayload, LoginRequest, LoginResponse, RegisterRequest, RegisterResponse, User} from "./apiDef";
export * from "./apiDef";
import * as models from "./models";
(window as any).models = models;
import * as path from 'path';
import * as log from 'loglevel';

function getData<T>(response: AxiosResponse<T>): T {
    return response.data
}

//#region Axios shortcuts

function retrieveOrNull<T>(path: string): Promise<T | null> {
    return new Promise<T | null>(function (resolve) {
        axios.get<T>(path)
            .then(getData).then(resolve)
            .catch(() => resolve(null))
    })
}

function putNoContent(path: string, data?: any): Promise<void > {
    return axios.put(path, data).then(() => undefined)
}

function post<T>(path: string, data?: any): Promise<T> {
    return axios.post(path, data).then(getData)
}

function delete_(path: string): Promise<void> {
    return axios.delete<void>(path).then(getData)
}

//#endregion

class CrudAdapter<T, TPayload> {
    private readonly basePath: string

    constructor(basePath: string) {
        this.basePath = basePath
    }

    get(id: number): Promise<T | null> {
        return retrieveOrNull( path.join(this.basePath, id + ''))
    }

    update(id: number, data: TPayload): Promise<void> {
        return putNoContent(path.join(this.basePath, id + ''), data)
    }

    delete(id: number): Promise<void> {
        return delete_(path.join(this.basePath, id + ''))
    }

    create(data: TPayload): Promise<T> {
        return post(this.basePath, data)
    }
}

class AuthenticationModule {
    login(data: LoginRequest) {
        return post<LoginResponse>('/auth/login', data)
            .then(data => {
                this.setToken(data.accessToken);
                return data
            });
    }

    setToken(token: string): void {
        localStorage['__tok'] = token;
        AuthenticationModule.updateToken();
        this.syncUserFromServer();
    }

    static updateToken(): void {
        axios.defaults.headers['Authorization'] = localStorage['__tok'] ? 'Bearer ' + localStorage['__tok'] : null;
    }

    static getToken(): string | null {
        return localStorage['__tok']
    }

    static retrieveUser(): Promise<User | null> {
        return retrieveOrNull<User>('/auth/user')
    }

    syncUserFromServer(silent: boolean = false): Promise<User | null> {
        if (!silent)
            store.commit('setUserSyncing', true);
        return AuthenticationModule.retrieveUser()
            .then((user: User | null) => {
                if (!silent)
                    store.commit('setUserSyncing', false);
                AuthenticationModule.setUser(user)
                return user;
            });
    }

    getUser(): User | null {
        return store.state.user
    }

    register(data: RegisterRequest) {
        return axios.post<RegisterResponse>('/auth/register', data)
            .then((resp) => {
                this.setToken(resp.data.login.accessToken);
                AuthenticationModule.setUser(resp.data.user);
                console.log(resp.data);
                return resp.data
            });
    }

    static getAuthentication(): string | null {
        let val = localStorage.getItem('authentication')
        val = val ? `Bearer ${val}` : null;
        return val
    }

    private static setUser(user: User) {
        store.state.user = user
    }
}

class Api {
    private isReady: boolean = false;

    public auth = new AuthenticationModule();
    public readonly courses = new CrudAdapter<Course, CoursePayload>('courses');
    public readonly users = new CrudAdapter<User, never>('users');


    constructor() {
        this.prepare();
    }

    //#region Api private methods

    private prepare() {
        axios.defaults.baseURL = location.origin + '/api';
        AuthenticationModule.updateToken();
        Promise.all([
            this.auth.syncUserFromServer() as Promise<any>
        ]).then(() => {
            EventBus.$emit('api-ready');
            this.isReady = true;
        });
    }

    //#endregion

    ready(): Promise<void> {
        if (this.isReady)
            return Promise.resolve();
        else
        {
            return new Promise((resolve) => {
                function resolveWrapper() {
                    EventBus.$off('api-ready', resolveWrapper);
                    resolve();
                }

                EventBus.$on('api-ready', resolveWrapper);
            });
        }
    }
}

const api = new Api();
const auth = api.auth;
const courses = api.courses;
const users = api.users;
export {
    api,
    auth,
    courses,
    users,
    log
}
