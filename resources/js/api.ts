import axios, {AxiosRequestConfig, AxiosResponse} from "axios"
import {EventBus} from "./bus";
import {store} from "./store";
import {LoginRequest, LoginResponse, RegisterRequest, RegisterResponse} from "./apiDef";
export * from "./apiDef";
import * as models from "./models";
(window as any).models = models;
import * as path from 'path';
import * as log from 'loglevel';
import {makeModel, Course, Model, ModelType, User} from "./models";
import {ICourse, ICoursePaginationData} from "./models/course";
import {IUnit} from "./models/unit";
import Pagination, {IPagination} from "./models/pagination";
import {IUser} from "./models/user";

function getData<T>(response: AxiosResponse<T>): T {
    return response.data
}

//#region Axios shortcuts

function retrieveOrNull<T>(path: string, opts?: AxiosRequestConfig): Promise<T | null> {
    return new Promise<T | null>(function (resolve) {
        axios.get<T>(path, opts)
            .then(getData)
            .then(resolve)
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

class CrudAdapter<
    T extends Model<TData>,
    TData extends object,
    TPaginationData extends object = TData> {

    private readonly basePath: string;
    private readonly model: ModelType<TData, T>;

    constructor(basePath: string, model: ModelType<TData, T>) {
        this.basePath = basePath;
        this.model = model;
    }

    get(id: number): Promise<T | null> {
        return retrieveOrNull<TData>( path.join(this.basePath, id + '')).then(makeModel(this.model))
    }

    update(id: number, data: TData): Promise<void> {
        return putNoContent(path.join(this.basePath, id + ''), data)
    }

    delete(id: number): Promise<void> {
        return delete_(path.join(this.basePath, id + ''))
    }

    create(data: TData): Promise<T> {
        return post<TData>(this.basePath, data).then(makeModel(this.model))
    }

    pagination(page: number = 1): Promise<Pagination<TPaginationData>> {
        return retrieveOrNull<IPagination<TPaginationData>>(this.basePath, {params: {page}}).then(v => new Pagination(v))
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
        return retrieveOrNull<IUser>('/auth/user').then(makeModel(User))
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
                AuthenticationModule.setUser(new User(resp.data.user));
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

export type CreateUnitsResponse = {
    deleted: number[]
    created: IUnit[]
    updated: number[]
}

export type UnitPayload = {
    name: string
    about: string
    is_preview?: boolean
}

export type UpdateUnitPayload = {
    id: number
} & Partial<UnitPayload>

export type CreateUnitsRequest = {
    delete?: number[]
    order?: (number | string)[]
    new?: UnitPayload[],
    upd?: UpdateUnitPayload[]
}

class CourseAdapter extends CrudAdapter<Course, ICourse, ICoursePaginationData> {
    constructor() {
        super('courses', Course);
    }

    createUnits(course: Course | number, data: CreateUnitsRequest): Promise<CreateUnitsResponse> {
        return post('courses/' + (course instanceof Course ? course.id : course) + '/units', data)
    }
}

class Api {
    private isReady: boolean = false;

    public auth = new AuthenticationModule();
    public readonly courses = new CourseAdapter();
    public readonly users = new CrudAdapter('users', null);


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
(window as any).API = api;
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
