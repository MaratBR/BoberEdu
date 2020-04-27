export namespace dto {
    export type CategoryDto = {
        id: number,
        about: string,
        name: string
    };

    export type CategoryExDto = CategoryDto & {
        popular: CourseDto[]
    };

    export type CategoriesDto = {
        categories: CategoryDto[]
    };

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
        price: number,
        name: string,
        available: boolean,
        about: string,
        trialDays: number,
        requirements: CourseRequirements
    };

    export type CourseExDto = CourseDto & {
        units: UnitExDto[],
        category: {
            id: number,
            name: string
        }
    };

    export type CoursePageItemDto = CourseDto & {
        info: {
            uc: number,
            lc: number
        }
    };

    export type EnrollmentDto = {
        courseId: number,
        trialEnd: string | null,
        firstJoined: string
    };

    export type EnrollmentsDto = { enrolls: EnrollmentDto[] };

    export type EnrollmentStateDto = {
        hasAccess: boolean,
        enrolled: boolean,
        paymentSuccessful: boolean
    };

    export type LessonDto = {
        title: string
    };

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

    export type PaymentDto = {
        success: boolean,
        redirect: string | null,
        title: string,
        gateaway: string,
        ts: number
    };

    export type TeacherAssignmentDto = {
        since: string | null,
        until: string | null
    };

    export type TeacherDto = {
        id: number,
        fullName: string
    };

    export type UnitDto = {
        id: number,
        name: string,
        preview: boolean
    };

    export type UnitExDto = UnitDto & {
        lessons: LessonDto[]
    };

    export enum Sex {
        Male = 'm',
        Female = 'f',
        Unknown = 'u'
    }

    export type UserDto = {
        id: number,
        joinedAt: string,
        sex: Sex,
        name: string,
        roles: string[]
    };
}

export namespace requests {
    export type CreateCourse = {
        name: string,
        price?: number,
        about: string,
        signUpBeg?: string,
        signUpEnd?: string,
        available?: string
    };

    export type UpdateCourse = {
        name?: string,
        price?: number,
        about?: string,
        signUpBeg?: string,
        signUpEnd?: string,
        available?: string
    };

    export type UpdateCourseUnits = {
        delete: number[],
        order: string[],
        upd: {
            id: number,
            name?: string,
            about?: string
        }[]
    };

    export type CreatePayment = {
        gateaway: string,
        data: any
    }
}

export type CreditCardData = {
    cvv: string,
    holder: string,
    number: string,
    expiryMonth: string,
    expiryYear: string
};
