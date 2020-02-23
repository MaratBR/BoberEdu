<template>
    <page title="Login">
        <form class="form" @submit.prevent="onSubmit" v-if="!$store.getters.isAuthenticated">
            <div class="form__control">
                <label class="form__label" for="InputLogin">Login</label>
                <input class="input" id="InputLogin" type="text" v-model="name">
            </div>
            <div class="form__control">
                <label class="form__label" for="InputPwd">Password</label>
                <input class="input" id="InputPwd" type="password" v-model="password">
            </div>
            <div v-if="result && !result.success" class="notification notification--danger">Credentials doesn't match</div>

            <div class="form__control">
                <button class="btn btn--primary">Log in</button>
            </div>

        </form>
        <section v-else>
            <h2>Hold on a sec</h2>
        </section>
    </page>
</template>

<script lang="ts">
    import Page from "./Page.vue";
    import {auth, LoginResponse} from "../../api";
    import Vue from "vue";

    export default Vue.extend({
        name: "LoginPage",
        components: {Page},
        data() {
            return {
                name: localStorage['lastLoginAttempt'] || '',
                password: '',
                result: null as LoginResponse | null
            }
        },
        methods: {
            onSubmit() {
                if (this.$store.getters.isAuthenticated) {
                    this.$router.push('/');
                    return;
                }
                localStorage['lastLoginAttempt'] = this.name;
                auth.login({name: this.name, password: this.password})
                    .then(r => {
                        this.$router.push('/')
                    })
                    .catch(e => {
                        this.result = e.response ? e.response.data : null
                    });
            }
        }
    })
</script>

<style scoped>

</style>
