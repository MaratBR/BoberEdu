import StoreModuleBase from "./StoreModuleBase";
import {dto} from "@common";

export default class LessonsModule extends StoreModuleBase {
    async get(id: number): Promise<dto.LessonExDto> {
        return this.client.get<dto.LessonExDto>('lessons/' + id).then(r => r.data)
    }
}
