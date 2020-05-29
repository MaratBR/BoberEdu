import {Action} from "vuex-simple";
import StoreModuleBase from "@common/store/StoreModuleBase";
import {dto, requests} from "@common";
import {DeletePayload, UpdatePayload} from "@common/store/utils";


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
    deleteCourse({id, reason}: DeletePayload): Promise<void> {
        return this.client.delete('courses/' + id, {data: {reason}}).then(r => r.data)
    }

    @Action()
    createCategory(d: requests.CreateCategory): Promise<dto.CategoryDto> {
        return this.client.post('courses/categories', d).then(r => r.data)
    }

    @Action()
    updateCategory(d: UpdatePayload<requests.UpdateCategory>): Promise<void> {
        return this.client.put('courses/categories/' + d.id, d.data).then(r => r.data)
    }

    @Action()
    updateLessonsOrder(d: UpdatePayload<requests.UpdateLessonsOrder>): Promise<void> {
        return this.client.put('courses/' + d.id + '/ordnung-muss-sein', d.data).then(r => r.data)
    }

    @Action()
    async getUnit(unitId: number): Promise<dto.StandaloneUnitDto> {
        return this.client.get('admin/courses/units/' + unitId).then(this._g)
    }
}
