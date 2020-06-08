import VueRouter from "vue-router";
import {RawLocation} from "vue-router/types/router";

import App from "@common/components/sections/App.vue";
import {getCommonStore} from "@common/store";
import NotFound from "@common/components/pages/NotFound.vue";

type ValidationResult = {
    to: string,
    params?: any
}

//#region b64 utility functions

// https://stackoverflow.com/questions/30106476/using-javascripts-atob-to-decode-base64-doesnt-properly-decode-utf-8-strings
function b64Encode(str) {
    return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g,
        function toSolidBytes(match, p1) {
            return String.fromCharCode(+p1);
        }));
}

function b64Decode(str) {
    return decodeURIComponent(atob(str).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));
}

//#endregion


let router = new VueRouter({
    routes: [
        {
            path: '/playground',
            component: () => import(
                /* webpackChunkName: "playground" */
                '@app/dev/Playground.vue'),
        },
        {
            path: "/admin",
            name: "admin",
            component: () => import(
                /* webpackChunkName: "admin-panel" */
                '@admin/components/AdminPanel.vue'),
            meta: {
                requiresAdmin: true
            },
            children: [
                {
                    path: 'courses/all',
                    name: 'admin__courses',
                    component: () => import(
                        /* webpackChunkName: "a-courses" */
                        '@admin/components/courses/CoursesList.vue')
                },
                {
                    path: 'courses/new',
                    name: 'admin__courses_new',
                    component: () => import(
                        /* webpackChunkName: "a-course-form" */
                        '@admin/components/courses/CourseForm.vue')
                },
                {
                    path: 'courses/:id',
                    name: 'admin__courses_edit',
                    component: () => import(
                        /* webpackChunkName: "a-course-form" */
                        '@admin/components/courses/CourseForm.vue'),
                    props({params}) {
                        return {
                            id: +params.id
                        }
                    }
                },
                {
                    path: 'courses/:id/units',
                    name: 'admin__courses_edit_units',
                    component: () => import(
                        /* webpackChunkName: "a-course-units-form" */
                        '@admin/components/courses/LessonOrderForm.vue'),
                    props({params, query}) {
                        return {
                            courseId: +params.id,
                            selectedId: +query.i || null
                        }
                    }
                },
                {
                    path: 'lessons/new/:id',
                    name: 'admin__lessons_new',
                    component: () => import(
                        /* webpackChunkName: "a-lesson-form" */
                        '@admin/components/courses/LessonForm.vue'),
                    props({params}) {
                        return {
                            unitId: +params.id
                        }
                    }
                },
                {
                    path: 'lessons/:id',
                    name: 'admin__lessons_edit',
                    component: () => import(
                        /* webpackChunkName: "a-lesson-form" */
                        '@admin/components/courses/LessonForm.vue'),
                    props({params}) {
                        return {
                            id: +params.id
                        }
                    }
                },
                {
                    path: 'categories',
                    name: 'admin__categories',
                    component: () => import(
                        /* webpackChunkName: "a-categories" */
                        '@admin/components/courses/Categories.vue')
                },

                {
                    path: 'teachers/all',
                    name: 'admin__teachers',
                    component: () => import(
                        /* webpackChunkName: "a-teachers" */
                        '@admin/components/teachers/TeachersList.vue'),
                    props({query}) {
                        return {
                            page: +query.p || 0
                        }
                    }
                },

                {
                    path: 'teachers/new/:id',
                    name: 'admin__teachers_new_for_user',
                    component: () => import(
                        /* webpackChunkName: "a-teachers-form" */
                        '@admin/components/teachers/TeacherForm.vue'),
                    props({params}) {
                        return {
                            userId: +params.id
                        }
                    }
                },
                {
                    path: 'teachers/new',
                    name: 'admin__teachers_new',
                    component: () => import(
                        /* webpackChunkName: "a-teachers-form" */
                        '@admin/components/teachers/TeacherForm.vue')
                },
                {
                    path: 'teachers/:id',
                    name: 'admin__teachers_edit',
                    component: () => import(
                        /* webpackChunkName: "admin-teachers-form" */
                        '@admin/components/teachers/TeacherForm.vue'),
                    props({params}) {
                        return {
                            id: +params.id
                        }
                    }
                },
                {
                    path: 'users/all',
                    name: 'admin__users',
                    component: () => import(
                        /* webpackChunkName: "admin-users" */
                        '@admin/components/users/UsersList.vue')
                },
                {
                    path: 'users/new',
                    name: 'admin__users_new',
                    component: () => import(
                        /* webpackChunkName: "admin-user-form" */
                        '@admin/components/users/UserForm.vue')
                },
                {
                    path: 'users/:id',
                    name: 'admin__users_edit',
                    component: () => import(
                        /* webpackChunkName: "admin-user-form" */
                        '@admin/components/users/UserForm.vue'),
                    props: ({params}) => ({id: +params.id})
                },
                {
                    path: '*',
                    component: NotFound
                }

            ]
        },
        {
            path: '',
            component: App,
            children: [
                {
                    path: '/u/:id',
                    name: 'profile',
                    component: () => import(
                        /* webpackChunkName: "profile" */
                        '@common/components/profile/Profile.vue'),
                },
                {
                    path: '/me/settings',
                    name: 'profile_settings',
                    component: () => import(
                        /* webpackChunkName: "profile-settings" */
                        "@common/components/profile/ProfileSettings.vue"),
                    meta: {
                        requiresAuth: true
                    }
                },
                {
                    path: '/login',
                    component: () => import(
                        /* webpackChunkName: "login" */
                        "@common/components/pages/LoginPage.vue"),
                    name: 'login'
                },
                {
                    path: '/logout',
                    component: () => import(
                        /* webpackChunkName: "logout" */
                        "@common/components/pages/LogoutPage.vue"),
                    name: 'logout'
                },
                {
                    path: '/register',
                    component: () => import(
                        /* webpackChunkName: "register" */
                        "@common/components/pages/RegisterPage.vue"),
                    name: 'register'
                },
                {
                    path: '/category/:id',
                    component: () => import(
                        /* webpackChunkName: "category" */
                        '@common/components/courses/Category.vue'),
                    name: 'category',
                    props({params}) {
                        return {
                            categoryId: +params.id || 0
                        }
                    }
                },
                {
                    path: '/categories',
                    component: () => import(
                        /* webpackChunkName: "categories" */
                        '@common/components/courses/Categories.vue'),
                    name: 'categories'
                },
                {
                    path: '/course/:id',
                    component: () => import(
                        /* webpackChunkName: "course-view" */
                        '@common/components/courses/Course.vue'),
                    name: 'course'
                },
                {
                    path: '/course/:id/purchase',
                    name: 'purchase_course',
                    component: () => import(
                        /* webpackChunkName: "purchase" */
                        '@common/components/courses/PurchaseCourse.vue')
                },
                {
                    name: 'lesson',
                    path: '/lesson/:v',
                    component: () => import(
                        /* webpackChunkName: "lesson" */
                        '@common/components/courses/Lesson.vue'),
                    beforeEnter: (to, from, next) => {
                        if (to.params.v && /^[1-9]\d*_[1-9]\d*$/.test(to.params.v)) {
                            next()
                        } else {
                            next({
                                name: 'oops',
                                params: {
                                    kind: 'invalidLessonId'
                                }
                            })
                        }
                    },
                    props(opts) {
                        let v = opts.params.v.split('_');
                        return {
                            courseId: +v[0],
                            lessonId: +v[1],
                        }
                    }
                },
                {
                    name: 'teacher',
                    component: () => import(
                        /* webpackChunkName: "teacher" */
                        '@common/components/teacher/Teacher.vue'
                        ),
                    path: '/teacher/:id',
                    props({params}) {
                        return {
                            id: +params.id
                        }
                    }
                },
                {
                    name: 'search',
                    path: '/search',
                    component: () => import(
                        /* webpackChunkName: "search" */
                        '@common/components/pages/SearchPage.vue'
                        ),
                    props({query}) {
                        return {
                            q: query.q
                        }
                    }
                },
                {
                    name: 'oops',
                    path: '/oops/:kind',
                    component: null
                },
                {
                    path: '*',
                    component: NotFound
                }
            ]
        }
    ],
    mode: 'history'
})


router.beforeEach((to, from, next: (to?: RawLocation | void) => void) => {

    let storeProxy = getCommonStore();

    for (let match of to.matched)


    if (to.matched.some(r => r.meta.requiresAuth) && !storeProxy.isAuthenticated) {
        next({
            name: 'login'
        })
    } else if (to.matched.some(r => r.meta.requiresAdmin) && !storeProxy.isAdmin) {
        next({
            name: 'oops',
            params: {
                kind: '403'
            }
        })
    } else {
        next()
    }
});

export default router;
