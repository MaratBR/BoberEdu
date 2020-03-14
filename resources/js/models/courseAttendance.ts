import {ITimestamps} from "./model";

export interface CourseAttendance extends ITimestamps {
    purchase_id: number
    preview: boolean
    user_id: number
    gifted_by_id: number
}


