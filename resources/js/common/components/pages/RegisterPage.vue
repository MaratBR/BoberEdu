import {Sex} from "../../api";
import {Sex} from "../../api";
<template>
    <page title="Register">
        <form class="form" @submit.prevent="onSubmit">
            <div class="form__control">
                <label class="form__label" for="InputUsername">Name</label>
                <input class="input" id="InputUsername" type="text" v-model="name">
            </div>

            <div class="form__control">
                <label class="form__label" for="InputEMail">E-Mail</label>
                <input class="input" id="InputEMail" type="text" v-model="email">
            </div>

            <div class="form__control">
                <label class="form__label" for="InputPwd">Password</label>
                <input class="input" id="InputPwd" type="password" v-model="password">
            </div>

            <error v-if="errors" :error="errors" />

            <div class="form__control">
                <button class="btn btn--primary">Register</button>
            </div>

        </form>
    </page>
</template>

<script lang="ts">
    import Page from "./Page.vue";
    import {Component} from "vue-property-decorator";
    import Error from "@common/components/utils/Error.vue";
    import {StoreComponent} from "@common/components/utils";
    import {getError} from "@common/utils";




    @Component({
        components: {Error, Page}
    })
    export default class RegisterPage extends StoreComponent {
        email = '';
        password = '';
        name = '';
        displayName = null
        errors = null;
        submitting = false;


        async onSubmit() {
            try {
                await this.store.register({
                    name: this.name,
                    email: this.email,
                    displayName: this.displayName,
                    password: this.password
                })
                await this.$router.push({ name: 'login' })
            }
            catch (e) {
                this.errors = getError(e)
            }
            finally {
                this.submitting = true;
            }
        }
    }
</script>

<style scoped>

</style>
