import {ITimestamps} from "./model";

export interface CourseAttendance extends ITimestamps {
    course_id: number
    preview: boolean
    user_id: number
    active: boolean
    id: number
}


