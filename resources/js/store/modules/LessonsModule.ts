import StoreModuleBase from "./StoreModuleBase";
import {Action} from "vuex-simple";
import {dto} from "../dto";
import LessonExDto = dto.LessonExDto;

export default class LessonsModule extends StoreModuleBase {
    @Action()
    async get(id: number): Promise<LessonExDto> {
        return this.client.get('lessons/' + id).then(r => r.data)
    }
}
