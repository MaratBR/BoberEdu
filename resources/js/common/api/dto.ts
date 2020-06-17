
export namespace dto {
    //#region Categories

    export type CategoryDto = {
        id: number,
        name: string
    };

    export type CategoryExDto = CategoryDto & {
        about: string,
        bgImage: string | null,
        color: string,
        coursesCount: number,
        studentsCount: number
    };

    export type CategoriesDto = {
        categories: CategoryExDto[]
    };

    //#endregion

    //#region Courses

    type CourseRequirements = {
        signUp: {
            beg: string,
            end: string,
            purchasable: string
        }
    };

    export type CourseDto = {
        id: number,
        rating: number,
        ratingVotes: number,
        price: number,
        name: string,
        available: boolean,
        about: string,
        trialDays: number,
        summary: string,
        image: string | null,
        requirements: CourseRequirements,
    };

    export type CourseExDto = CourseDto & {
        units: UnitExDto[],
        teachers: TeacherDto[],
        category: dto.CategoryDto
    };

    export type CoursePageItemDto = CourseDto & {
        info: {
            uc: number,
            lc: number
        }
    };

    //#endregion

    //#region Enrollments

    export type EnrollmentDto = {
        courseId: number,
        trialEnd: string | null,
        firstJoined: string
    };

    export type EnrollmentsDto = { enrolls: EnrollmentDto[] };

    export type EnrollmentStateDto = {
        hasAccess: boolean,
        enrolled: boolean,
        paymentSuccessful: boolean,
        trialEnd: string
    };

    //#endregion

    //#region Lessons

    export type LessonDto = {
        title: string
        id: number,
        summary: string
    };

    export type LessonExDto = LessonDto & {
        content: string
        courseId: number
        courseName: string
        unitId: number
        unitName: string
    };

    //#endregion

    //#region Utils

    export type PaginationDto<T> = {
        data: T[],
        meta: {
            total: number,
            perPage: number,
            page: number,
            lastPage: number,
            next: string | null,
            prev: string | null
        }
    };

    export type Done = {
        done: true
    }

    //#endregion

    //#region Payments

    export type PaymentDto = {
        success: boolean,
        redirect: string | null,
        title: string,
        gateaway: string,
        ts: number
    };

    //#endregion

    //#region Teachers

    export type TeacherApplicationDto = {
        user: dto.UserDto,
        id: number,
        approved: boolean | null,
        approvedBy: dto.UserDto | null
    }

    export type TeacherApplicationExDto = TeacherApplicationDto & {
        degree: string,
        extra: string,
        fullName: string,
        education: string,
        location: string,
    }

    export type TeacherApprovalState = 'approved' | 'rejected' | 'awaiting' | null

    export type TeacherDto = {
        id: number,
        fullName: string,
        about: string,
        avatar: string | null
    };

    export type TeacherExDto = TeacherDto & {
        about: string,
        links: {
            yt: string | null,
            vk: string | null,
            fb: string | null,
            twitter: string | null,
            linkedIn: string | null,
            web: string | null
        }
    }

    export type TeacherProfileDto = TeacherExDto & {
        courses: dto.CoursePageItemDto[]
    }

    export type AdminTeacherDto = TeacherExDto & {
        user: UserDto
    }

    //#endregion

    //#region Units

    export type UnitDto = {
        id: number,
        name: string,
        preview: boolean
    };

    export type StandaloneUnitDto = UnitDto & {
        courseName: string,
        courseId: number
    };

    export type CourseUnitsDto = {
        courseId: number,
        courseName: string,
        units: dto.UnitExDto[]
    }

    export type UnitExDto = UnitDto & {
        lessons: LessonDto[],
        about: string
    };

    //#endregion

    //#region Users

    export type UserDto = {
        id: number,
        joinedAt: string,
        name: string,
        admin: boolean,
        status: string,
        about: string,
        avatar: string,
    };

    export type SelfUserDto = UserDto & {
        isTeacher: boolean
    };

    export type UserProfileDto = {
        user: UserDto,
        courses: {
            since: string,
            activated: boolean,
            trialEnd: string,
            course: {
                name: string,
                id: string
            }
        }[]
    }

    export type UserSettingsDto = {
        id: number,
        about: string,
        name: string,
        avatar: string,
        email: string
    };

    export type AdminUserDto = UserDto & {
        teacher: AdminTeacherDto,
        email: string,
    }

    //#endregion

    //#region Admin panel overview and audit

    export type AdminOverviewDto = {
        teacherApplications: {
            awaitingReview: number,
            rejected: number,
            approved: number,
        }
    }

    export type AuditDto = {
        id: number,
        sub: {
            type: string,
            id: number,
            display: string
        },
        ua: string,
        c: string | null,
        a: string,
        ex: any,
        ip: string,
        user: {
            id: number,
            name: string
        }
    }

    //#endregion

    //#region Teachers' dashboard

    export type TeacherDashboardDto = {
        courses: dto.CoursePageItemDto[],
        income: number
    }

    //#endregion
}

export namespace requests {
    export type CreateCourse = {
        categoryId: number,
        name: string,
        price?: number,
        about: string,
        summary: string,
        signUpBeg?: string,
        signUpEnd?: string,
        available?: boolean,
        trialLength?: number
    };

    export type UpdateCourse = {
        name?: string,
        price?: number,
        about?: string,
        summary?: string,
        signUpBeg?: string,
        signUpEnd?: string,
        available?: boolean,
        trialLength?: number
    };

    export type NewUnitPayload = {
        name: string,
        about: string,
        preview?: boolean
    }

    export type UpdateUnitPayload = {
        id: number,
        name?: string,
        preview?: boolean,
        about?: string
    }

    export type UpdateCourseUnits = {
        delete: number[],
        order: string[],
        upd: UpdateUnitPayload[],
        new: NewUnitPayload[]
    };

    export type CreatePayment = {
        gateaway: string,
        data: any
    }

    export type UpdateUser = {
        name?: string,
        status?: string,
        about?: string,
    };

    export type Login = {
        name: string,
        password: string,
        rememberMe: boolean
    }

    export type Register = {
        name: string,
        password: string,
        email: string,
    }

    export type CreateCategory = {
        name: string,
        about: string,
        color?: string
    }

    export type UpdateCategory = Partial<CreateCategory>;

    export type UpdateLessonsOrder = {
        units: {
            id: number,
            order: number[]
        }[]
    }

    export type CreateLesson = {
        title: string,
        summary: string,
        content?: string,
        unitId: number
    }

    export type UpdateLesson = {
        title?: string,
        summary?: string,
        content?: string
    }

    type TeacherLinks = {
        linkWeb?: string,
        linkYt?: string,
        linkLinkedIn?: string,
        linkVk?: string,
        linkFb?: string,
        linkTwitter?: string
    }

    export type CreateTeacher = {
        fullName: string,
        userId: number,
        comment: string,
        about: string
    } & TeacherLinks

    export type UpdateTeacher = {
        fullName: string,
        about: string
    } & TeacherLinks

    export type PromoteRequest = {
        admin: boolean,
        reason: string
    }

    export type SubmitTeacherApprovalForm = {
        education: string,
        fullName: string,
        degree: string,
        extra: string,
        location: string
    }
}

export type CreditCardData = {
    cvv: string,
    holder: string,
    number: string,
    expiryMonth: string,
    expiryYear: string
};
