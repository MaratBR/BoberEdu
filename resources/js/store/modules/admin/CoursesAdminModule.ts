import StoreModuleBase from "../StoreModuleBase";
import {Action} from "vuex-simple";
import {requests, dto} from "../../dto";


export default class CoursesModule extends StoreModuleBase {
    @Action()
    update({id, data}: {id: number, data: requests.UpdateCourse}): Promise<void> {
        return this.client.put('courses/' + id, data).then(r => r.data)
    }

    @Action()
    create(data: requests.CreateCourse): Promise<dto.CourseDto> {
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
    enroll(id: number): Promise<void> {
        return this.client.patch('enrollment/' + id + '/enroll').then(r => r.data)
    }

    @Action()
    disenroll(id: number): Promise<void> {
        return this.client.patch('enrollment/' + id + '/disenroll').then(r => r.data)
    }

    @Action()
    status(id: number): Promise<dto.EnrollmentStateDto> {
        return this.client.get('enrollment/' + id + '/status').then(r => r.data)
    }

    @Action()
    getEnrolls(): Promise<dto.EnrollmentsDto> {
        return this.client.get('enrollment/yours').then(r => r.data)
    }
}
