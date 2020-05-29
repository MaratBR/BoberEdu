import {Action, Module, registerModule, useModule} from "vuex-simple";
import {vuexStore} from "@common/store";
import CoursesAdminModule from "@admin/store/CoursesAdminModule";
import client from "@common/axios";
import LessonsAdminModule from "@admin/store/LessonsAdminModule";
import TeachersAdminModule from "@admin/store/TeachersAdminModule";
import {AxiosResponse} from "axios";
import {dto, requests} from "@common";
import {UpdatePayload} from "@common/store/utils";

export class AdminModule {
    @Module() courses = new CoursesAdminModule(client);
    @Module() lessons = new LessonsAdminModule(client);
    @Module() teachers = new TeachersAdminModule(client);

    protected _get<T>(response: AxiosResponse<T>): T {
        return response.data
    }

    //#region Users

    @Action()
    async getUser(userId: number): Promise<dto.AdminUserDto> {
        return client.get('admin/users/' + userId).then(this._get)
    }

    //#endregions

    //#region Teachers

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


}

registerModule(vuexStore, ['dyn_admin'], new AdminModule());

export function getAdminModule(): AdminModule {
    return useModule(vuexStore, ['dyn_admin'])
}
