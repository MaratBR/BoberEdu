import {Action, Getter, Module, Mutation, State} from "vuex-simple";

import client from "@common/axios";
import {AuthModule, CoursesModule, LessonsModule, PaymentsModule} from "@common/store";
import {dto, requests} from "@common";
import {randomId} from "@common/utils";

export default class CommonStore {
    @Module() public courses = new CoursesModule(client);
    @Module() public payments = new PaymentsModule(client);
    @Module() public lessons = new LessonsModule(client);

    //#region Auth

    @State() user: dto.UserDto | null = null;
    @State() logoutToken: string = null;
    @State() loggingIn: boolean = false;

    @Mutation() SET_LOGGING_IN(val: boolean) {
        this.loggingIn = val;
    }

    @Mutation() SET_USER(user: dto.UserDto | null) {
        this.user = user;
        this.logoutToken = localStorage.getItem('logoutToken')
        if (user === null) {
            localStorage.setItem('logoutToken', null)
        } else {
            if (!this.logoutToken) {
                this.logoutToken = randomId(8)
                localStorage.setItem('logoutToken', this.logoutToken)
            }
        }
    }

    @Getter() get isAuthenticated(): boolean {
        return !!this.user
    }

    @Getter() get isAdmin(): boolean {
        return this.user && this.user.admin
    }

    @Action()
    async init() {
        await this.fetchCurrentUser();
    }

    @Action()
    async fetchCurrentUser(): Promise<void> {
        try {
            let {data} = await client.get<dto.UserDto>('auth/user');
            this.SET_USER(data)
        } catch (e) {
            this.clearSession()
        }
    }

    @Action()
    async login(request: requests.Login): Promise<void> {
        try {
            if (this.isAuthenticated)
                throw new Error('You are already logged in!');
            this.SET_LOGGING_IN(true);
            await client.post('auth/login', request);
            await this.fetchCurrentUser();
        } finally {
            this.SET_LOGGING_IN(false)
        }
    }

    @Action()
    async register(request: requests.Register) {
        if (this.isAuthenticated)
            throw new Error('You are already logged in! You can\'t register a new account, log out first please');

        await client.post('auth/register', request);
    }

    @Action()
    async logout() {
        await client.post('auth/logout')
        this.clearSession()
    }

    @Action()
    clearSession() {
        this.SET_USER(null);
    }

    //#endregion

    //#region Teachers

    @Action()
    getTeacher(id: number): Promise<dto.TeacherProfileDto> {
        return client.get('teachers/' + id).then(r => r.data)
    }

    @Action()
    sendTeacherApprovalForm(data: requests.SubmitTeacherApprovalForm): Promise<dto.Done> {
        return client.put('teachers/approval-form', data).then(r => r.data)
    }

    @Action()
    getTeacherApprovalState(): Promise<dto.TeacherApprovalState> {
        return client.get('teachers/approval-form').then(r => r.data.state)
    }

    //#endregion

    //#region Users

    userProfile(id: number): Promise<dto.UserProfileDto> {
        return client.get('users/' + id + '/profile').then(r => r.data)
    }

    setUserStatus(status: string): Promise<void> {
        return client.put('users/profile/status', {
            status
        }).then(r => r.data)
    }

    userSettings(): Promise<dto.UserSettingsDto> {
        return client.get('users/profile/settings').then(r => r.data)
    }

    usernameIsTaken(username: string): Promise<boolean> {
        return client.get('users/username-taken/' + username).then(r => r.data)
    }

    updateUser(d: requests.UpdateUser): Promise<void> {
        return client.patch('users/profile', d).then(r => r.data)
    }

    uploadAvatar(file: File): Promise<string> {
        return client.put('users/profile/avatar', file).then(r => r.data.id)
    }

    //#endregion

    //#region Courses

    @Action()
    searchCourses(q): Promise<dto.PaginationDto<dto.CoursePageItemDto>> {
        return client.get('courses/search', {params: {q}}).then(r=>r.data)
    }

    @Action()
    getCourse(id: number): Promise<dto.CourseExDto> {
        return client.get('courses/' + id).then(r => r.data)
    }

    @Action()
    getUnit(id: number): Promise<dto.StandaloneUnitDto> {
        return client.get('courses/units/' + id).then(r => r.data)
    }

    @Action()
    paginateCourses(page: number): Promise<dto.PaginationDto<dto.CoursePageItemDto>> {
        return client.get('courses', {params: {page}}).then(r => r.data)
    }

    @Action()
    getCategory(id: number): Promise<dto.CategoryExDto> {
        return client.get('courses/categories/' + id).then(r => r.data)
    }

    @Action()
    getCoursesFromCategory({id, page}: {id: number, page?: number}): Promise<dto.PaginationDto<dto.CoursePageItemDto>>  {
        return client.get('courses/categories/' + id + '/courses', {
            params: {
                page
            }
        }).then(r => r.data)
    }

    @Action()
    getCategories(): Promise<dto.CategoriesDto> {
        return client.get('courses/categories').then(r => r.data)
    }

    @Action()
    removeRate(courseId: number): Promise<void> {
        return client.delete('courses/' + courseId + '/rate').then(r => r.data)
    }

    @Action()
    setCourseRate({courseId, value}: {courseId: number, value: number}): Promise<void> {
        value = Math.round(value);
        value = Math.min(Math.max(value, 1), 5);
        return client.put('courses/' + courseId + '/rate', { value }).then(r => r.data)
    }

    //#endregion

    //#region Enrollment

    @Action()
    enroll(id: number): Promise<void> {
        return client.patch('enrollment/' + id + '/enroll').then(r => r.data)
    }

    @Action()
    disenroll(id: number): Promise<void> {
        return client.patch('enrollment/' + id + '/disenroll').then(r => r.data)
    }

    @Action()
    enrollmentStatus(id: number): Promise<dto.EnrollmentStateDto> {
        return client.get('enrollment/' + id + '/status').then(r => r.data)
    }

    @Action()
    getEnrolls(): Promise<dto.EnrollmentsDto> {
        return client.get('enrollment/yours').then(r => r.data)
    }

    @Action()
    async getPayments(): Promise<dto.PaginationDto<dto.PaymentDto>> {
        return client.get('payments').then(r => r.data)
    }

    //#endregion

}

