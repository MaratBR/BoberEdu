import VueRouter from "vue-router";
import {Store, store} from "./store";
import {useStore} from "vuex-simple";
import {RawLocation} from "vue-router/types/router";

let router = new VueRouter({
    routes: [
        {
            path: '/u/:id',
            name: 'profile',
            component: () => import(
                /* webpackChunkName: "profile" */
                './components/pages/profile/Profile.vue'),
        },
        {
            path: '/me/settings',
            name: 'profile_settings',
            component: () => import(
                /* webpackChunkName: "profile" */
                './components/pages/profile/ProfileSettings.vue'),
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/login',
            component: () => import(
                /* webpackChunkName: "login" */
                './components/pages/LoginPage.vue'),
            name: 'login'
        },
        {
            path: '/register',
            component: () => import(
                /* webpackChunkName: "register" */
                './components/pages/RegisterPage.vue'),
            name: 'register'
        },
        {
            path: '/category/:id',
            component: () => import(
                /* webpackChunkName: "category" */
                './components/courses/Category.vue'),
            name: 'category'
        },
        {
            path: '/categories',
            component: () => import(
                /* webpackChunkName: "categories" */
                './components/courses/Categories.vue'),
            name: 'courses'
        },
        {
            path: '/c/new',
            component: () => import(/* webpackChunkName: "course-form" */ './components/admin/CourseForm.vue'),
            name: 'create_course'
        },
        {
            path: '/c/:id',
            component: () => import(/* webpackChunkName: "course-view" */ './components/courses/Course.vue'),
            name: 'course'
        },
        {
            path: '/c/:id/edit',
            component: () => import(/* webpackChunkName: "edit-course" */ './components/pages/EditCourseForm.vue'),
            name: 'edit_course'
        },
        {
            path: '/c/:id/edit/units',
            component: () => import(/* webpackChunkName: "edit-course-units" */ './components/pages/CourseUnitsForm.vue'),
            name: 'edit_course_units'
        },
        {
            path: '/c/:id/purchase',
            name: 'purchase_course',
            component: () => import(/* webpackChunkName: "purchase" */ './components/courses/PurchaseCourse.vue')
        },
        {
            name: 'lesson',
            path: '/lesson/:v',
            component: () => import(/* webpackChunkName: "lesson" */ './components/courses/Lesson.vue'),
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
            name: 'oops',
            path: '/oops/:kind',
            component: null
        },


        {
            path: "/admin",
            name: "admin",
            component: () => import('./components/admin/AdminPanel.vue'),
            meta: {
                requiresAdmin: true
            }
        }
    ],
    mode: 'history'
})


router.beforeEach((to, from, next: (to?: RawLocation | void) => void) => {

    let storeProxy = useStore<Store>(store);

    for (let match of to.matched)

    if (to.matched.some(r => r.meta.requiresAuth) && !storeProxy.auth.isAuthenticated) {
        next({
            name: 'login'
        })
    } else if (to.matched.some(r => r.meta.requiresAdmin) && !storeProxy.auth.isAdmin) {
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
