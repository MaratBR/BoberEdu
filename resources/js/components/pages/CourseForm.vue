<template>
    <page :title="courseData.name || 'New course'">
        <template v-slot:header>
            <div class="d--flex">
                <router-link class="btn" :to="{name: 'course', params: {id: courseData.id}}">View course</router-link>
                <router-link class="ml--1 btn" v-if="courseData.isPersistent" :to="{name: 'course', params: {id: courseData.id}}">View course</router-link>
            </div>
        </template>

        <form class="form" @submit.prevent="onSubmit">
            <validation-provider v-slot="{errors}" rules="required">
            <div class="form__control">
                <label for="Name">Name</label>
                <input type="text" class="input" :aria-invalid="errors.length !== 0" id="Name" v-model="courseData.name">
                <span class="input__error">{{errors[0]}}</span>
            </div>
            </validation-provider>

            <validation-provider v-slot="{errors}" rules="required|min_value:0">
                <div class="form__control">
                    <label for="Price">Price</label>
                    <input type="text" class="input" :aria-invalid="errors.length !== 0" id="Price" v-model="courseData.price" />
                    <span v-for="err in errors" class="input__error">{{err}}</span>
                </div>
            </validation-provider>

            <div>
                <input
                    id="HasSignupPeriod"
                    type="checkbox"
                    class="input"
                    v-model="hasSignUpPeriod">
                <label for="HasSignupPeriod">Has sign up period</label>
                <div v-show="hasSignUpPeriod" class="d--flex fxw--wrap">
                    <div class="form__control mr--2">
                        <label class="form__label">Starts at</label>
                        <date-input @input="$forceUpdate()" v-model="courseData.sign_up_beg" class="input" output-format="YYYY-MM-DD" />
                    </div>

                    <div class="form__control">
                        <label class="form__label">Ends at</label>
                        <date-input @input="$forceUpdate()" v-model="courseData.sign_up_end" output-format="YYYY-MM-DD" class="input" />
                    </div>
                </div>
            </div>

            <validation-provider v-slot="{errors}" rules="required">
                <div class="form__control">
                    <label>Summary</label>
                    <editor v-model="courseData.about" />
                    <span v-for="err in errors" class="input__error">{{err}}</span>
                </div>
            </validation-provider>

            <error v-for="(error, index) in errors">{{index}}: {{error.join(', ')}}</error>

            <input :disabled="submitting" type="submit" class="btn btn--primary" value="Save">
        </form>

        <units-editor :course="courseData" />
    </page>
</template>

<script lang="ts">
    import Page from "./Page.vue";
    import {Editor} from '@toast-ui/vue-editor'
    import {CoursePayload, courses} from "../../api";
    import Loader from "../misc/Loader.vue";
    import Error from "../misc/Error.vue";
    import UnitsEditor from "./UnitsEditor.vue";
    import Course from "../../models/course";
    import DateInput from "../misc/DateInput.vue";
    import {PropValidator} from "vue/types/options";
    import {makeModel} from "../../models";

    export default {
        name: "CourseForm",
        components: {DateInput, UnitsEditor, Error, Loader, Page, editor: Editor},
        data() {
            return {
                courseData: new Course({}),
                signUpPeriodErr: '',
                hasSignUpPeriod: false,
                loading: true,
                errors: null,
                submitting: false
            }
        },
        props: {
            course: {
                type: Object,
                default: null
            } as PropValidator<Course>
        },
        methods: {
            onSubmit() {
                if (this.submitting)
                    return;
                this.submitting = true;

                let data = this.courseData.getStagedChanges();
                let promise: Promise<any> = this.course ?
                    courses.update(this.course.id, data) :
                    courses.create(data).then(makeModel(Course)).then(c => this.courseData = c);
                promise
                    .then(r => console.log(r))
                    .catch(err => this.errors = err.response.data.errors)
                    .finally(() => this.submitting = false)
            },
            init() {
                if (this.course)
                    this.courseData = this.course;
                this.courseData.enableStaging();
                this.hasSignUpPeriod = !!this.courseData.sign_up_beg;
            }
            /*signUpPeriodIsValid() {
                return (!this.courseData.sign_up_beg && !this.courseData.sign_up_end) ||
                    this.courseData.sign_up_end && this.courseData.sign_up_beg && +this.courseData.sign_up_end > +this.courseData.sign_up_beg
            }*/
        },
        created(): void {
            this.init()
        },
        watch: {
            course() {
                this.init()
            }
        },
        computed: {

        }
    }
</script>

<style scoped>

</style>
