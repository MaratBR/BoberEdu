
//#region Error-related stuff

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

//#endregion

//#region Common elements

type IDModel = {
    id: number
}

type DateString = string;

type Timestamps = {
    created_at: DateString
}

//#endregion

//#region User-related objects section

type LoginResponseSuccess = {
    accessToken: string,
    expiresIn: number,
    tokenType: string,
    success: true
};

export type LoginResponse = LoginResponseSuccess;

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

export type RegisterResponse = {
    user: User,
    login: LoginResponseSuccess
}

export type User = {
    name: string
} & IDModel & Timestamps

//#endregion

export type UnitPayload = {
    name: string,
    is_preview: boolean,
    about: string,
    order_num: 0
}

export type Unit = IDModel & Timestamps & UnitPayload & {
    course_id: number
}

//#region Course-related objects

export type CoursePayload = {
    about: string
    sign_up_beg: DateString | null
    sign_up_end: DateString | null
    price: number
    name: string
}

export type Course = CoursePayload & Timestamps & IDModel & {
    units: Unit[]
};

function removeTimestampsAndId<T extends object>(obj: T & Timestamps & IDModel): T {

    let {created_at, id, ...result} = obj;
    // @ts-ignore
    return result as T;
}

export function extractCoursePayload(course: Course): CoursePayload {
    return removeTimestampsAndId(course);
}

//#endregion
