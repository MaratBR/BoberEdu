import {Action, registerModule, useModule} from "vuex-simple";
import {vuexStore} from "@common/store";
import {dto, requests} from "@common";
import client from "@common/axios";
import {UpdatePayload} from "@common/store/utils";

export default class TeacherModule {
    @Action()
    getDashboard(): Promise<dto.TeacherDashboardDto> {
        return client.get('teacher-dashboard').then(r => r.data)
    }

    @Action()
    updateCourse(d: UpdatePayload<requests.UpdateCourse>): Promise<dto.CourseExDto> {
        return client.put('teacher-dashboard/courses/' + d.id, d.data).then(r => r.data)
    }

    @Action()
    createCourse(d: requests.CreateCourse): Promise<dto.CourseExDto> {
        return client.post('teacher-dashboard/courses', d).then(r => r.data)
    }

    @Action()
    uploadCourseImage(d: UpdatePayload<File>): Promise<string> {
        return client.post('teacher-dashboard/courses/' + d.id + '/image', d.data).then(r => r.data.id)
    }

    @Action()
    updateCourseUnits(d: UpdatePayload<requests.UpdateCourseUnits>): Promise<dto.Done> {
        return client.put('teacher-dashboard/courses/' + d.id + '/units', d.data).then(r => r.data)
    }

    @Action()
    updateLessonsOrder(d: UpdatePayload<requests.UpdateLessonsOrder>): Promise<dto.Done> {
        return client.put('teacher-dashboard/courses/' + d.id + '/lessons-order', d.data).then(r => r.data)
    }

    @Action()
    updateLesson(d: UpdatePayload<requests.UpdateLesson>): Promise<dto.Done> {
        return client.put('teacher-dashboard/lessons/' + d.id, d.data).then(r => r.data)
    }

    @Action()
    createLesson(d: requests.CreateLesson): Promise<dto.Done> {
        return client.post('teacher-dashboard/lessons', d).then(r => r.data)
    }
}

let adminModuleInitialized = false;

export function getTeacherModule(): TeacherModule {
    if (!adminModuleInitialized) {
        adminModuleInitialized = true;
        registerModule(vuexStore, ['dyn_teacher'], new TeacherModule());
    }
    return useModule(vuexStore, ['dyn_teacher'])
}
