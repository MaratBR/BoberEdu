<template>
    <page title="New course">
        <form class="form" @submit="onSubmit">
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
                    <input type="text" class="input" :aria-invalid="errors.length !== 0" id="Price" v-model="data.price">
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
                <div v-if="hasSignUpPeriod" class="d--flex">
                    <div class="form__control m--2">
                        <label for="SignUpBeg" class="form__label">Starts at</label>
                        <cleave id="SignUpBeg" type="text" :options="{date: true, datePattern: ['Y', 'm', 'd']}" placeholder="YYYY/MM/DD" v-model="sign_up_beg_raw" class="input" />
                    </div>

                    <div class="form__control m--2">
                        <label for="SignUpEnd" class="form__label">Ends at</label>
                        <cleave id="SignUpEnd" type="text" :options="{date: true, datePattern: ['Y', 'm', 'd']}" placeholder="YYYY/MM/DD" v-model="sign_up_end_raw" class="input" />
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
                sign_up_beg_raw: '',
                sign_up_end_raw: '',
                signUpPeriodErr: '',
                hasSignUpPeriod: true
            }
        },
        methods: {
            onSubmit() {
                api
            }
        },
        watch: {
            sign_up_beg_raw(newVal: string) {
                if (newVal.length !== 6) {
                    newVal += '0'.repeat(6 - newVal.length)
                }
                this.data.sign_up_beg = new Date(+newVal.substr(0, 4), +newVal.substr(4, 2), +newVal.substr(6, 2))
            },
            sign_up_end_raw(newVal: string) {
                if (newVal.length !== 6) {
                    newVal += '0'.repeat(6 - newVal.length)
                }
                this.data.sign_up_beg = new Date(+newVal.substr(0, 4), +newVal.substr(4, 2), +newVal.substr(6, 2))
            }
        }
    }
</script>

<style scoped>

</style>
