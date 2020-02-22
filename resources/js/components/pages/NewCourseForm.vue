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


            <validation-provider v-slot="{errors}" rules="required">
                <div class="form__control">
                    <label>Summary</label>
                    <editor v-model="data.summary" />
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
                <div v-if="hasSignUpPeriod" class="card mt--2">
                    <validation-provider>
                        <div class="form__control">
                            <vue-date-pick
                                :format="'YYYY.MM.DD'"
                                v-model="data.sign_up_beg"
                                class="input--vdp" />
                        </div>
                    </validation-provider>
                </div>
            </div>

        </form>
    </page>
</template>

<script>
    import Page from "./Page";
    import 'tui-editor/dist/tui-editor.css';
    import 'tui-editor/dist/tui-editor-contents.css';
    import 'codemirror/lib/codemirror.css';
    import {Editor} from '@toast-ui/vue-editor'
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
                hasSignUpPeriod: true
            }
        },
        methods: {
            onSubmit() {

            }
        }
    }
</script>

<style scoped>

</style>
