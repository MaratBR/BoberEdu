import {Action} from "vuex-simple";
import StoreModuleBase from "@common/store/StoreModuleBase";
import {dto, requests} from "@common";
import {UpdatePayload} from "@common/store/utils";


export default class CoursesAdminModule extends StoreModuleBase {
    @Action()
    update({id, data}: {id: number, data: requests.UpdateCourse}): Promise<void> {
        return this.client.put('courses/' + id, data).then(r => r.data)
    }

    @Action()
    create(data: requests.CreateCourse): Promise<dto.CourseExDto> {
        return this.client.post('courses', data).then(r => r.data)
    }

    @Action()
    updateUnits({id, data}: {id: number, data: requests.UpdateCourseUnits}): Promise<void> {
        return this.client.put('courses/' + id + '/units', data).then(r => r.data)
    }

    @Action()
    deleteCourse(id: number): Promise<void> {
        return this.client.delete('courses/' + id).then(r => r.data)
    }

    @Action()
    createCategory(d: requests.CreateCategory): Promise<dto.CategoryDto> {
        return this.client.post('courses/categories', d).then(r => r.data)
    }

    @Action()
    updateCategory(d: UpdatePayload<requests.UpdateCategory>): Promise<void> {
        return this.client.put('courses/categories/' + d.id, d.data).then(r => r.data)
    }
}
