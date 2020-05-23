import VueRouter from "vue-router";
import {useStore} from "vuex-simple";
import {RawLocation} from "vue-router/types/router";

import App from "@common/components/sections/App.vue";
import {getCommonStore} from "@common/store";

let router = new VueRouter({
    routes: [
        {
            path: '*',
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
                    name: 'category'
                },
                {
                    path: '/categories',
                    component: () => import(
                        /* webpackChunkName: "categories" */
                        '@common/components/courses/Categories.vue'),
                    name: 'courses'
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
                    name: 'oops',
                    path: '/oops/:kind',
                    component: null
                }
            ]
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

    let storeProxy = getCommonStore();

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
