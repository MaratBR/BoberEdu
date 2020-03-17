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
import {CourseAttendance} from "./models/courseAttendance";
import {Purchase} from "./models/purchase";

//#region Axios shortcuts

function getData<T>(response: AxiosResponse<T>): T {
    return response.data
}

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
                return data
            });
    }

    setAuthorization(token?: string): void {
        axios.defaults.headers['Authorization'] = token ? 'Bearer ' + token : null;
    }

    static retrieveUser(): Promise<IUser | null> {
        return retrieveOrNull<IUser>('/auth/user')
    }

    syncUserFromServer(): Promise<IUser | null> {
        return AuthenticationModule.retrieveUser()
            .then((user: IUser | null) => {
                return user;
            });
    }

    register(data: RegisterRequest) {
        return post<RegisterResponse>('/auth/register', data);
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

export type PurchaseCourseRequest = {
    gift_to?: number,
    preview?: boolean
};

export type AttendanceStatus = 'no' | 'yes' | 'preview' | 'preview_expired' | 'awaiting_payment' | 'cancelled';

export type AttendanceStatusResponse = {
    status: AttendanceStatus
}

class CourseAdapter extends CrudAdapter<Course, ICourse, ICoursePaginationData> {
    constructor() {
        super('courses', Course);
    }

    createUnits(course: Course | number, data: CreateUnitsRequest): Promise<CreateUnitsResponse> {
        return post('courses/' + (course instanceof Course ? course.id : course) + '/units', data)
    }

    attend(courseId: number, preview: boolean = false, giftTo?: number) {
        let data: PurchaseCourseRequest = {};
        if (preview)
            data.preview = preview;
        if (giftTo)
            data.gift_to = giftTo;
        return post<CourseAttendance>(`courses/${courseId}/attend`, data)
    }

    submitAttendance(courseId: number) {
        return post<Purchase>(`courses/${courseId}/attendance/submit`)
    }

    checkAttendanceStatus(courseId: number) {
        return retrieveOrNull<AttendanceStatusResponse>(`courses/${courseId}/attendance/status`)
            .then(result => result ? result.status : null)
    }

    getAttendance(courseId: number) {
        return retrieveOrNull(`courses/${courseId}/attendance`)
    }
}

class Api {
    private isReady: boolean = false;

    public auth = new AuthenticationModule();
    public readonly courses = new CourseAdapter();
    public readonly users = new CrudAdapter('users', User);


    constructor() {
        this.prepare();
    }

    //#region Api private methods

    private prepare() {
        axios.defaults.baseURL = location.origin + '/api';
        this.auth.setAuthorization(store.state.auth.authToken);
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
// TODO Remove that line
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
