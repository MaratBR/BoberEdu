<template>
    <page :title="data.name + (course ? '' : ' (new course)')">
        <form class="form" @submit.prevent="onSubmit">
            <validation-provider v-slot="{errors}" rules="required">
            <div class="form__control">
                <label for="Name">Name</label>
                <input type="text" class="input" :aria-invalid="errors.length !== 0" id="Name" v-model="data.name">
                <span class="input__error">{{errors[0]}}</span>
            </div>
            </validation-provider>

            <validation-provider v-slot="{errors}" rules="required|min_value:0">
                <div class="form__control">
                    <label for="Price">Price</label>
                    <input type="text" class="input" :aria-invalid="errors.length !== 0" id="Price" v-model="data.price" />
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
                        <label for="SignUpBeg" class="form__label">Starts at</label>
                        <cleave :aria-invalid="+data.sign_up_end < +data.sign_up_beg" id="SignUpBeg" type="text" :options="{date: true, datePattern: ['Y', 'm', 'd']}" placeholder="YYYY/MM/DD" v-model="data.sign_up_beg" class="input" />
                        <span v-show="+data.sign_up_end < +data.sign_up_beg" class="input__error">"Starts at" must go <b>before</b> "Ends at"</span>
                    </div>

                    <div class="form__control">
                        <label for="SignUpEnd" class="form__label">Ends at</label>
                        <cleave :aria-invalid="+data.sign_up_end < +data.sign_up_beg" id="SignUpEnd" type="text" :options="{date: true, datePattern: ['Y', 'm', 'd']}" placeholder="YYYY/MM/DD" v-model="data.sign_up_end" class="input" />
                    </div>
                </div>
            </div>

            <validation-provider v-slot="{errors}" rules="required">
                <div class="form__control">
                    <label>Summary</label>
                    <editor v-model="data.about" />
                    <span v-for="err in errors" class="input__error">{{err}}</span>
                </div>
            </validation-provider>

            <input :disabled="submitting" type="submit" class="btn btn--primary" value="Save">
        </form>

        <units-editor :units="course ? course.units : []" />
    </page>
</template>

<script lang="ts">
    import Page from "./Page.vue";
    import {Editor} from '@toast-ui/vue-editor'
    import {CoursePayload, courses, extractCoursePayload} from "../../api";
    import Loader from "../misc/Loader.vue";
    import Error from "../misc/Error.vue";
    import UnitsEditor from "./UnitsEditor.vue";
    import {makeModel, Model} from "../../model";

    const defaultCoursePayload = {
        name: '',
        about: '',
        price: 0,
        sign_up_beg: null,
        sign_up_end: null
    } as CoursePayload;

    console.log(Editor);
    export default {
        name: "CourseForm",
        components: {UnitsEditor, Error, Loader, Page, editor: Editor},
        data() {
            return {
                data: null,
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
            }
        },
        methods: {
            onSubmit() {
                if (this.submitting)
                    return;
                this.submitting = true;
                console.log(this.data);
                let data = this.data instanceof Proxy ? this.data.getStaged() : this.data;
                return;
                let promise: Promise<any> = this.course ?
                    courses.update(this.course.id, data) :
                    courses.create(data).then(c => this.course = c);
                promise.finally(() => this.submitting = false)
            },
            init() {
                if (this.course) {
                    this.data = makeModel(extractCoursePayload(this.course));
                } else {
                    this.data = {...defaultCoursePayload};
                }
            }
        },
        created(): void {
            this.init()
        },
        watch: {
            course() {
                this.init()
            }
        }
    }
</script>

<style scoped>

</style>
