import {Store} from "vuex";
import {Course} from "../../models";
import {api, AttendanceStatus} from "../../api";
import course, {ICoursePaginationData} from "../../models/course";
import Pagination from "../../models/pagination";

export type CoursesState = {
    cache: {
        [key: string]: {
            val: any,
            ts: number
        }
    },
    maxCacheAgeMs: number
}

export type PurchaseModel = {
    course: Course,
    attendanceStatus: AttendanceStatus
}

export default {
    state: {
        cache: {},
        maxCacheAgeMs: 1000 * 60 * 20
    },
    namespaced: true,
    mutations: {
        SET_CACHE_ENTRY({cache, maxCacheAgeMs}, {val, key}) {
            cache[key] = {val, ts: +new Date() + maxCacheAgeMs}
        },

        REMOVE_CACHE_ENTRY({cache}, course: Course) {
            delete cache[`course.${course.id}`]
        },

        MARK_AS_CLEAN({cache, maxCacheAgeMs}, key: string) {
            if (cache[key])
                cache[key].ts = +new Date() + maxCacheAgeMs;
        }
    },
    actions: {
        getCourse({commit, getters}, id: number): Promise<Course | null> {
            let course = getters.course(id);
            if (course)
                return Promise.resolve(course);
            return api.courses.get(id)
                .then(course => {
                    if (course)
                        commit('SET_CACHE_ENTRY', {
                            key: `course.${course.id}`,
                            val: course
                        });
                    return course
                })
        },
        updateCourse({commit}, {id, data}): Promise<void> {
            return  api.courses.update(id, data)
                .then(() => commit('MARK_AS_CLEAN', id))
        },
        updateUnits(_context, {data, course}) {
            return api.courses.createUnits(course, data)
        },
        getCoursesPage({commit}, {page}): Promise<Pagination<ICoursePaginationData>> {
            return api.courses.pagination(page)
        },

        purchase(_context, {course, preview}) {
            return api.courses.purchase(course, preview)
        },

        submitPurchase(_context, attendanceId) {
            return api.courses.submitPurchase({
                attendance_id: attendanceId
            })
        },

        checkAttendanceStatus(_context, courseId) {
            return api.courses.checkAttendanceStatus(courseId)
        },

        getPurchaseModel({dispatch}, courseId) {
            return Promise.all([
                dispatch('getCourse', courseId),
                dispatch('checkAttendanceStatus', courseId)
            ]).then(([course, attendanceStatus]) => {
                return {
                    attendanceStatus,
                    course
                } as PurchaseModel
            })
        }

    },
    getters: {
        course: state => id => {
            let entry = state.cache[`course.${id}`];
            if (entry) {
                if (entry.ts <= +new Date()) {
                    delete state.cache[`course.${id}`];
                    return null;
                }
                return entry.course
            }
            return null
        }
    }
}
