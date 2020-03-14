import VueRouter from "vue-router";
import {store} from "./store/store";

export default new VueRouter({
    routes: [
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
            path: '/user/:id',
            component: () => import(/* webpackChunkName: "profile" */ './components/pages/ProfilePage.vue'),
            name: 'user'
        },

        {
            path: '/c',
            component: () => import(
                /* webpackChunkName: "courses-list" */
                './components/pages/CoursesList.vue'),
            name: 'courses'
        },
        {
            path: '/c/new',
            component: () => import(/* webpackChunkName: "course-form" */ './components/pages/CourseForm.vue'),
            name: 'create_course'
        },
        {
            path: '/c/:id',
            component: () => import(/* webpackChunkName: "course-view" */ './components/pages/CourseView.vue'),
            name: 'course'
        },
        {
            path: '/c/:id/edit',
            component: () => import(/* webpackChunkName: "edit-course" */ './components/pages/EditCourseForm.vue'),
            name: 'edit_course'
        },
        {
            path: '/c/:id/purchase',
            name: 'purchase_course',
            component: () => import(/* webpackChunkName: "purchase" */ './components/pages/PurchaseCourse.vue')
        }
    ],
    mode: 'history'
})

