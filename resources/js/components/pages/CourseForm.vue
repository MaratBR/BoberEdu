<template>
    <page title="New course">
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
                    <editor v-model="data.summary" />
                    <span v-for="err in errors" class="input__error">{{err}}</span>
                </div>
            </validation-provider>

            <input type="submit" class="btn btn--primary" value="Save">
        </form>
    </page>
</template>

<script lang="ts">
    import Page from "./Page.vue";
    import {Editor} from '@toast-ui/vue-editor'
    import {api} from "../../api";

    console.log(Editor);
    export default {
        name: "NewCourseForm",
        components: {Page, editor: Editor},
        data() {
            return {
                data: {
                    name: '',
                    summary: '',
                    price: 0,
                    sign_up_beg: null,
                    sign_up_end: null
                },
                signUpPeriodErr: '',
                hasSignUpPeriod: false
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
                api.courses.create(this.data)
            }
        },
        created(): void {
            if (this.course) {

            }
        }
    }
</script>

<style scoped>

</style>
