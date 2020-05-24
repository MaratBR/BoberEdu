import {Sex} from "@app/store/modules/AuthModule";

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
        requirements: CourseRequirements,
        teachers: TeacherDto[]
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
        paymentSuccessful: boolean,
        trialEnd: string
    };

    export type LessonDto = {
        title: string
        id: number
    };

    export type LessonExDto = LessonDto & {
        content: string
        courseId: number
        courseName: string
        unitId: number
        unitName: string
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
        lessons: LessonDto[],
        about: string
    };

    export enum Sex {
        Male = 'm',
        Female = 'f',
        Unknown = 'u'
    }

    export const sexes: {[key: string]: string} = {
        [Sex.Unknown]: 'Unknown',
        [Sex.Female]: 'Female',
        [Sex.Male]: 'Male'
    };

    export type UserDto = {
        id: number,
        joinedAt: string,
        sex: Sex,
        name: string,
        roles: string[],
        status: string,
        about: string,
        avatar: string
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
        sex: Sex,
        email: string,
        name: string,
        avatar: string
    };
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
        available?: string,
    };

    export type UpdateCourse = {
        name?: string,
        price?: number,
        about?: string,
        summary?: string,
        signUpBeg?: string,
        signUpEnd?: string,
        available?: string
    };

    export type NewUnitPayload = {
        name: string,
        about: string,
        preview?: boolean
    }

    export type UpdateUnitPayload = {
        id: number,
        name?: string,
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
        sex?: dto.Sex
    };

    export type Login = {
        name: string,
        password: string
    }

    export type Register = {
        name: string,
        password: string,
        email: string,
        sex?: dto.Sex
    }
}

export type CreditCardData = {
    cvv: string,
    holder: string,
    number: string,
    expiryMonth: string,
    expiryYear: string
};
