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

            <div class="form__control">
                <label for="InputSex">Sex</label>
                <select v-once name="sex" id="InputSex" v-model="sex">
                    <option v-for="(v, s) in sexes" :value="s">{{v}}</option>
                </select>
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
    import {Sex, sexes as sexesList} from "../../store/modules/AuthModule";
    import {Component, Vue} from "vue-property-decorator";
    import {Store} from "../../store";
    import {useStore} from "vuex-simple";
    import Error from "../misc/Error.vue";


    @Component({
        components: {Error, Page}
    })
    export default class RegisterPage extends Vue {
        email = '';
        password = '';
        name = '';
        sex = Sex.Unknown;
        errors = null;
        submitting = false;
        sexes = sexesList;

        store: Store = useStore(this.$store);

        async onSubmit() {
            try
            {
                await this.store.auth.register({
                    name: this.name,
                    email: this.email,
                    sex: this.sex,
                    password: this.password
                })
                await this.$router.push({ name: 'login' })
            }
            catch (e) {
                this.errors = e.response.data
            }
            finally {
                this.submitting = true;
            }
        }
    }
</script>

<style scoped>

</style>
