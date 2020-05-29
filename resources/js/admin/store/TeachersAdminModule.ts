import StoreModuleBase from "@common/store/StoreModuleBase";
import {Action} from "vuex-simple";
import {dto, requests} from "@common";
import {UpdatePayload} from "@common/store/utils";

export default class TeachersAdminModule extends StoreModuleBase {
    @Action()
    get(id: number): Promise<dto.AdminTeacherDto> {
        return this.client.get('admin/teachers/' + id).then(this._g)
    }

    @Action()
    create(d: requests.CreateTeacher): Promise<dto.AdminTeacherDto> {
        return this.client.post('admin/teachers', d).then(this._g)
    }

    @Action()
    update({id, data}: UpdatePayload<requests.UpdateTeacher>): Promise<dto.AdminTeacherDto> {
        return this.client.post('admin/teachers/' + id, data).then(this._g)
    }

    @Action()
    paginate(page: number = 1): Promise<dto.PaginationDto<dto.TeacherDto>> {
        return this.client.get('admin/teachers', {
            params: {page}
        }).then(this._g)
    }
}
