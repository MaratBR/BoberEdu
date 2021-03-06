import {Action, Module, registerModule, useModule} from "vuex-simple";
import {vuexStore} from "@common/store";
import client from "@common/axios";
import {AxiosResponse} from "axios";
import {dto, requests} from "@common";
import {DeletePayload, UpdatePayload} from "@common/store/utils";

export class AdminModule {
    protected _get<T>(response: AxiosResponse<T>): T {
        return response.data
    }

    @Action()
    getOverview(): Promise<dto.AdminOverviewDto> {
        return client.get('admin/overview').then(this._get)
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
    assignTeacher(d: { teacherId: number, courseId: number, reason: string }): Promise<void> {
        return client.put('admin/assignments/' + d.teacherId + '/' + d.courseId + '/assignment', {reason: d.reason}).then(this._get)
    }

    @Action()
    revokeTeacher(d: { teacherId: number, courseId: number, reason: string }): Promise<void> {
        return client.delete('admin/assignments/' + d.teacherId + '/' + d.courseId + '/assignment', {
            data: {reason: d.reason}
        }).then(this._get)
    }

    @Action()
    searchTeachers(d: { query: any; page: number }): Promise<dto.PaginationDto<dto.TeacherDto>> {
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
        return client.put('admin/teachers/' + id + '/avatar', data).then(r => r.data.id)
    }

    @Action()
    getTeacherApplications({page, f}: {page: number, f?: string}): Promise<dto.PaginationDto<dto.TeacherApplicationDto>> {
        return client.get('admin/teachers/approval-form', {params: {page, f}}).then(this._get);
    }

    @Action()
    getTeacherApplication(id: number): Promise<dto.TeacherApplicationExDto> {
        return client.get('admin/teachers/approval-form/' + id).then(this._get);
    }

    @Action()
    approveTeacherApplication(id: number): Promise<dto.Done> {
        return client.put('admin/teachers/approval-form/' + id + '/approve').then(this._get);
    }

    @Action()
    rejectTeacherApplication(id: number): Promise<dto.Done> {
        return client.put('admin/teachers/approval-form/' + id + '/reject').then(this._get);
    }

    //#endregion

    //#region Courses

    uploadCourseImage(d: UpdatePayload<File>): Promise<string> {
        return client.put('admin/courses/' + d.id + '/image', d.data).then(r => r.data.id);
    }

    @Action()
    searchCourses(d: {query: any; page: number, category?: number}): Promise<dto.PaginationDto<dto.CoursePageItemDto>> {
        return client.get('admin/courses/search', {params: {page: d.page, q: d.query, c: d.category}})
            .then(this._get);
    }

    @Action()
    updateCourse({id, data}: {id: number, data: requests.UpdateCourse}): Promise<void> {
        return client.put('admin/courses/' + id, data).then(this._get)
    }

    @Action()
    createCourse(data: requests.CreateCourse): Promise<dto.CourseExDto> {
        return client.post('admin/courses', data).then(this._get)
    }

    @Action()
    updateCourseUnits({id, data}: {id: number, data: requests.UpdateCourseUnits}): Promise<void> {
        return client.put('admin/courses/' + id + '/units', data).then(this._get)
    }

    @Action()
    deleteCourse({id, reason}: DeletePayload): Promise<void> {
        return client.delete('admin/courses/' + id, {data: {reason}}).then(this._get)
    }

    @Action()
    updateLessonsOrder(d: UpdatePayload<requests.UpdateLessonsOrder>): Promise<void> {
        return client.put('admin/courses/' + d.id + '/ordnung-muss-sein', d.data).then(this._get)
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
    createCategory(d: requests.CreateCategory): Promise<dto.CategoryExDto> {
        return client.post('admin/categories', d).then(this._get)
    }

    @Action()
    updateCategory(d: UpdatePayload<requests.UpdateCategory>): Promise<void> {
        return client.put('admin/categories/' + d.id, d.data).then(this._get)
    }

    @Action()
    uploadCategoryImage(d: UpdatePayload<File>): Promise<void> {
        return client.put('admin/categories/' + d.id + '/image', d.data).then(this._get)
    }

    //#endregion

    //#region Lessons

    @Action()
    getLesson(id: number): Promise<dto.LessonExDto> {
        return client.get('admin/lessons/' + id).then(this._get)
    }

    @Action()
    updateLesson({id, data}: UpdatePayload<requests.UpdateLesson>): Promise<dto.LessonExDto> {
        return client.put('admin/lessons/' + id, data).then(this._get)
    }

    @Action()
    createLesson(d: requests.CreateLesson): Promise<dto.LessonExDto> {
        return client.post('admin/lessons', d).then(this._get)
    }

    @Action()
    deleteLessons(id: number): Promise<void> {
        return client.delete('admin/lessons/' + id).then(this._get)
    }

    //#endregion

    @Action()
    getAuditLog(page: number): Promise<dto.PaginationDto<dto.AuditDto>> {
        return client.get('admin/audit/all', {params: {page}}).then(this._get)
    }
}

let adminModuleInitialized = false;

export function getAdminModule(): AdminModule {
    if (!adminModuleInitialized) {
        adminModuleInitialized = true;
        registerModule(vuexStore, ['dyn_admin'], new AdminModule());
    }
    return useModule(vuexStore, ['dyn_admin'])
}
