import StoreModuleBase from "./StoreModuleBase";
import {Action} from "vuex-simple";
import {requests, dto} from "../dto";


export default class CoursesModule extends StoreModuleBase {
    @Action()
    get(id: number): Promise<dto.CourseExDto> {
        return this.client.get('courses/' + id).then(r => r.data)
    }

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
    paginate(page: number): Promise<dto.PaginationDto<dto.CoursePageItemDto>> {
        return this.client.get('courses', {params: {page}}).then(r => r.data)
    }

    @Action()
    deleteCourse(id: number): Promise<void> {
        return this.client.delete('courses/' + id).then(r => r.data)
    }

    @Action()
    getCategory(id: number): Promise<dto.CategoryExDto> {
        return this.client.get('courses/categories/' + id).then(r => r.data)
    }

    @Action()
    getCoursesFromCategory(id: number): Promise<dto.PaginationDto<dto.CoursePageItemDto>>  {
        return this.client.get('courses/categories/' + id + '/courses').then(r => r.data)
    }

    @Action()
    getCategories(): Promise<dto.CategoriesDto> {
        return this.client.get('courses/categories').then(r => r.data)
    }

    @Action()
    removeRate(courseId: number): Promise<void> {
        return this.client.delete('courses/' + courseId + '/rate').then(r => r.data)
    }

    @Action()
    setRate(courseId: number, value: number): Promise<void> {
        value = Math.round(value);
        value = Math.min(Math.max(value, 1), 5);
        return this.client.put('courses/' + courseId + '/rate', { value })
    }


    @Action()
    enroll(id: number): Promise<void> {
        return this.client.patch('enrollment/' + id + '/enroll')
    }

    @Action()
    disenroll(id: number): Promise<void> {
        return this.client.patch('enrollment/' + id + '/disenroll')
    }

    @Action()
    status(id: number): Promise<dto.EnrollmentStateDto> {
        return this.client.get('enrollment/' + id + '/status')
    }

    @Action()
    getEnrolls(): Promise<dto.EnrollmentsDto> {
        return this.client.get('enrollment/yours')
    }
}
