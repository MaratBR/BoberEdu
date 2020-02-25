import VueRouter from "vue-router";
import {store} from "./store";

export default new VueRouter({
    routes: [
        {
            path: '/login',
            component: () => import('./components/pages/LoginPage.vue')
        },
        {
            path: '/register',
            component: () => import('./components/pages/RegisterPage.vue')
        },
        {
            path: '/user/:id',
            component: () => import('./components/pages/ProfilePage.vue')
        },

        {
            path: '/c/new',
            component: () => import('./components/pages/CourseForm.vue')
        },
        {
            path: '/c/:id',
            component: () => import('./components/pages/CourseView.vue'),
            name: 'course'
        },
        {
            path: '/c/:id/edit',
            component: () => import('./components/pages/EditCourseForm.vue'),
            name: 'edit_course'
        }
    ],
    mode: 'history'
})

