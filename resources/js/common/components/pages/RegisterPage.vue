import {Sex} from "../../api";
import {Sex} from "../../api";
<template>
    <page title="Register">
        <form @submit.prevent="onSubmit">
            <div class="form-group">
                <label for="InputUsername">Name</label>
                <input class="form-control" id="InputUsername" type="text" v-model="name">
            </div>

            <div class="form-group">
                <label for="InputEMail">E-Mail</label>
                <input class="form-control" id="InputEMail" type="text" v-model="email">
            </div>

            <div class="form-group">
                <label for="InputPwd">Password</label>
                <input class="form-control" id="InputPwd" type="password" v-model="password">
            </div>

            <div class="form-group">
                <label for="InputPwd">Repeat password please</label>
                <input class="form-control" id="InputPwd" type="password" v-model="repeatPassword">
            </div>
            <span class="text-danger" v-if="dontMatch">Password don't match</span>

            <error v-if="errors" :error="errors" />

            <div class="form-group">
                <button class="btn btn-primary">Register</button>
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
        repeatPassword = '';
        name = '';
        errors = null;
        submitting = false;

        get dontMatch() {
            return this.password !== '' && this.password !== this.repeatPassword
        }

        async onSubmit() {
            try {
                await this.store.register({
                    name: this.name,
                    email: this.email,
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
