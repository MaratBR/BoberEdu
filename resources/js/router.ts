import VueRouter from "vue-router";

export default new VueRouter({
    routes: [
        {
            path: '/u/:id',
            name: 'profile',
            component: () => import(
                /* webpackChunkName: "profile" */
                './components/pages/Profile.vue'),
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
                './components/pages/Category.vue'),
            name: 'category'
        },
        {
            path: '/categories',
            component: () => import(
                /* webpackChunkName: "categories" */
                './components/pages/Categories.vue'),
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
            path: '/c/:id/edit/units',
            component: () => import(/* webpackChunkName: "edit-course-units" */ './components/pages/CourseUnitsForm.vue'),
            name: 'edit_course_units'
        },
        {
            path: '/c/:id/purchase',
            name: 'purchase_course',
            component: () => import(/* webpackChunkName: "purchase" */ './components/pages/PurchaseCourse.vue')
        }
    ],
    mode: 'history'
})

