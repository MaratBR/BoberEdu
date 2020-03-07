import VueRouter from "vue-router";
import {store} from "./store";

export default new VueRouter({
    routes: [
        {
            path: '/login',
            component: () => import(
                /* webpackChunkName: "login" */
                './components/pages/LoginPage.vue')
        },
        {
            path: '/register',
            component: () => import(
                /* webpackChunkName: "register" */
                './components/pages/RegisterPage.vue')
        },
        {
            path: '/user/:id',
            component: () => import(/* webpackChunkName: "profile" */ './components/pages/ProfilePage.vue')
        },

        {
            path: '/c',
            component: () => import(
                /* webpackChunkName: "courses-list" */
                './components/pages/CoursesList.vue')
        },
        {
            path: '/c/new',
            component: () => import(/* webpackChunkName: "course-form" */ './components/pages/CourseForm.vue')
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
        }
    ],
    mode: 'history'
})

