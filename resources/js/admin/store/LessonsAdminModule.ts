import StoreModuleBase from "@common/store/StoreModuleBase";
import {Action} from "vuex-simple";
import {dto, requests} from "@common";
import {UpdatePayload} from "@common/store/utils";

export default class LessonsAdminModule extends StoreModuleBase {
    @Action()
    get(id: number): Promise<dto.LessonExDto> {
        return this.client.get('admin/lessons/' + id).then(this._g)
    }

    @Action()
    update({id, data}: UpdatePayload<requests.UpdateLesson>): Promise<dto.LessonExDto> {
        return this.client.put('admin/lessons/' + id, data).then(this._g)
    }

    @Action()
    delete(id: number): Promise<void> {
        return this.client.delete('admin/lessons/' + id).then(this._g)
    }
}
