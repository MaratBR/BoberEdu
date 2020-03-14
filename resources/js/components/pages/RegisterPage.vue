import {Sex} from "../../api";
import {Sex} from "../../api";
<template>
    <page title="Register">
        <form class="form" @submit.prevent="onSubmit">
            <div class="form__control">
                <label class="form__label" for="InputUsername">Name</label>
                <input class="input" id="InputUsername" type="text" v-model="data.name">
            </div>

            <div class="form__control">
                <label class="form__label" for="InputEMail">E-Mail</label>
                <input class="input" id="InputEMail" type="text" v-model="data.email">
            </div>

            <div class="form__control">
                <label class="form__label" for="InputPwd">Password</label>
                <input class="input" id="InputPwd" type="password" v-model="data.password">
            </div>

            <div class="form__control">
                <label for="InputSex">Sex</label>
                <select v-once name="sex" id="InputSex" v-model="data.sex">
                    <option v-for="(v, s) in sexes" :value="s">{{v}}</option>
                </select>
            </div>

            <div class="notification notification--danger" v-for="err in Object.values(errors)">{{err.join(', ')}}</div>

            <div class="form__control">
                <button class="btn btn--primary">Register</button>
            </div>

        </form>
    </page>
</template>

<script lang="ts">
    import Page from "./Page.vue";
    import {auth, RegisterRequest, Sex, sexes} from "../../api";


    export default {
        name: "RegisterPage",
        components: {Page},
        data() {
            return {
                data: {
                    email: '',
                    password: '',
                    name: '',
                    sex: Sex.Unknown
                } as RegisterRequest,
                sexes,
                errors: {}
            }
        },
        methods: {
            onSubmit() {
                this.$store.dispatch('register', this.data)
                    .then(console.log)
            }
        }
    }
</script>

<style scoped>

</style>
