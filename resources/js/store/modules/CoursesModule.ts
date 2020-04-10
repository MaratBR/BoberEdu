import StoreModuleBase from "./StoreModuleBase";
import {Action} from "vuex-simple";
import {Pagination} from "../_utils";

export type Course = {
    id: number,
    sign_up_beg: string,
    sign_up_end: string,
    about: string,
    name: string,
    available: boolean,
    trial_length: number,
    price: number
}

export type CreateCourseDate = {
    name: string,
    price?: number,
    about: string,
    sign_up_beg?: string,
    sign_up_end?: string,
    available?: string
};

export type UpdateCourseData = {
    name?: string,
    price?: number,
    about?: string,
    sign_up_beg?: string,
    sign_up_end?: string,
    available?: string
};

export type UpdateCourseUnits = {
    delete: number[],
    order: string[],
    upd: {
        id: number,
        name?: string,
        about?: string
    }[]
};

export type CoursesPagination = Pagination<Course & {
    units_count: number,
    lessons_count: number
}>;

export type CourseEx = Course & {
    units: {
        name: string
        is_preview: boolean
        about: string
        lessons: string[]
    }[]
};

export type Purchase = {
    id: number,
    external_redirect_url: string,
    status: string
}

export type CourseAttendance = {
    user_id: number,
    course_id: number,
    active: boolean
};

export default class CoursesModule extends StoreModuleBase {
    @Action()
    get(id: number): Promise<CourseEx> {
        return this.client.get('courses/' + id).then(r => r.data)
    }

    @Action()
    update({id, data}: {id: number, data: UpdateCourseData}): Promise<void> {
        return this.client.put('courses/' + id, data).then(r => r.data)
    }

    @Action()
    create(data: CreateCourseDate): Promise<Course> {
        return this.client.post('courses', data).then(r => r.data)
    }

    @Action()
    updateUnits({id, data}: {id: number, data: UpdateCourseUnits}): Promise<void> {
        return this.client.put('courses/' + id + '/units', data).then(r => r.data)
    }

    @Action()
    paginate(page: number): Promise<CoursesPagination> {
        return this.client.get('courses', {params: {page}}).then(r => r.data)
    }

    @Action()
    getAttendance(id: number): Promise<CourseAttendance> {
        return this.client.get('courses/' + id + '/attendance').then(r => r.data)
    }

    @Action()
    join(id: number): Promise<void> {
        return this.client.post('courses/' + id + '/join')
    }

    @Action()
    purchase(id: number): Promise<Purchase> {
        return this.client.post('courses/' + id + '/purchase')
    }
}
