import {Action, Module, registerModule, useModule} from "vuex-simple";
import {vuexStore} from "@common/store";
import CoursesAdminModule from "@admin/store/CoursesAdminModule";
import client from "@common/axios";
import LessonsAdminModule from "@admin/store/LessonsAdminModule";
import TeachersAdminModule from "@admin/store/TeachersAdminModule";
import {AxiosResponse} from "axios";
import {dto, requests} from "@common";
import {DeletePayload, UpdatePayload} from "@common/store/utils";

export class AdminModule {
    @Module() courses = new CoursesAdminModule(client);
    @Module() lessons = new LessonsAdminModule(client);
    @Module() teachers = new TeachersAdminModule(client);

    protected _get<T>(response: AxiosResponse<T>): T {
        return response.data
    }

    //#region Users

    @Action()
    async uploadUserAvatar({id, data}: UpdatePayload<File>): Promise<string> {
        return client.put('admin/users/' + id + '/avatar', data, {
            headers: {
                'Content-Type': data.type
            }
        }).then(r => r.data.id)
    }

    @Action()
    createUser(data: requests.Register): Promise<dto.AdminUserDto> {
        return client.post('admin/users', data).then(this._get)
    }

    @Action()
    getUser(userId: number): Promise<dto.AdminUserDto> {
        return client.get('admin/users/' + userId).then(this._get)
    }

    @Action()
    updateUser({id, data}: UpdatePayload<requests.UpdateUser>): Promise<dto.AdminUserDto> {
        return client.put('admin/users/' + id, data).then(this._get)
    }

    @Action()
    promoteUser({id, data}: UpdatePayload<requests.PromoteRequest>): Promise<dto.AdminUserDto> {
        return client.put('admin/users/' + id + '/admin', data).then(this._get)
    }

    @Action()
    paginateUsers(d: { page: number, order?: string }): Promise<dto.PaginationDto<dto.AdminUserDto>> {
        return client.get('admin/users', {params: {page: d.page, order: d.order}}).then(this._get)
    }

    @Action()
    searchUsers(d: { page: number, query: string }): Promise<dto.PaginationDto<dto.AdminUserDto>> {
        return client.get('admin/users/search', {params: {page: d.page, q: d.query}}).then(this._get)
    }

    //#endregions

    //#region Teachers

    @Action()
    async searchTeachers(d: { query: any; page: number }): Promise<dto.PaginationDto<dto.TeacherDto>> {
        return client.get('admin/teachers/search', {
            params: {
                page: d.page,
                q: d.query
            }
        }).then(this._get);
    }

    @Action()
    getTeacher(id: number): Promise<dto.AdminTeacherDto> {
        return client.get('admin/teachers/' + id).then(this._get)
    }

    @Action()
    createTeacher(d: requests.CreateTeacher): Promise<dto.AdminTeacherDto> {
        return client.post('admin/teachers', d).then(this._get)
    }

    @Action()
    updateTeacher({id, data}: UpdatePayload<requests.UpdateTeacher>): Promise<dto.AdminTeacherDto> {
        return client.put('admin/teachers/' + id, data).then(this._get)
    }

    @Action()
    paginateTeachers(page: number = 1): Promise<dto.PaginationDto<dto.TeacherDto>> {
        return client.get('admin/teachers', {params: {page}}).then(this._get)
    }

    @Action()
    async uploadTeacherAvatar({id, data}: UpdatePayload<File>): Promise<string> {
        let d = (await data.stream().getReader().read()).value
        return client.put('admin/teachers/' + id + '/avatar', d).then(r => r.data.id)
    }

    //#endregion

    //#region Courses

    @Action()
    searchCourses(d: {query: any; page: number, category?: number}): Promise<dto.PaginationDto<dto.CoursePageItemDto>> {
        return client.get('admin/courses/search', {params: {page: d.page, q: d.query, c: d.category}})
            .then(this._get);
    }

    @Action()
    updateCourse({id, data}: {id: number, data: requests.UpdateCourse}): Promise<void> {
        return client.put('courses/' + id, data).then(this._get)
    }

    @Action()
    createCourse(data: requests.CreateCourse): Promise<dto.CourseExDto> {
        return client.post('courses', data).then(this._get)
    }

    @Action()
    updateCourseUnits({id, data}: {id: number, data: requests.UpdateCourseUnits}): Promise<void> {
        return client.put('courses/' + id + '/units', data).then(this._get)
    }

    @Action()
    deleteCourse({id, reason}: DeletePayload): Promise<void> {
        return client.delete('courses/' + id, {data: {reason}}).then(this._get)
    }

    @Action()
    updateLessonsOrder(d: UpdatePayload<requests.UpdateLessonsOrder>): Promise<void> {
        return client.put('courses/' + d.id + '/ordnung-muss-sein', d.data).then(this._get)
    }

    //#endregion

    //#region Units

    @Action()
    async getUnit(unitId: number): Promise<dto.StandaloneUnitDto> {
        return client.get('admin/courses/units/' + unitId).then(this._get)
    }

    //#endregion

    //#region Categories

    @Action()
    createCategory(d: requests.CreateCategory): Promise<dto.CategoryDto> {
        return client.post('courses/categories', d).then(this._get)
    }

    @Action()
    updateCategory(d: UpdatePayload<requests.UpdateCategory>): Promise<void> {
        return client.put('courses/categories/' + d.id, d.data).then(this._get)
    }

    //#endregion

}

registerModule(vuexStore, ['dyn_admin'], new AdminModule());

export function getAdminModule(): AdminModule {
    return useModule(vuexStore, ['dyn_admin'])
}
