<template>
    <page title="Login">
        <form class="form" @submit.prevent="onSubmit" v-if="!$store.getters['auth/isAuthenticated']">
            <div class="form__control">
                <label class="form__label" for="InputLogin">Login</label>
                <input class="input" id="InputLogin" type="text" v-model="name">
            </div>
            <div class="form__control">
                <label class="form__label" for="InputPwd">Password</label>
                <input class="input" id="InputPwd" type="password" v-model="password">
            </div>
            <div v-show="failed" class="notification notification--danger">Credentials doesn't match</div>

            <div class="form__control">
                <button class="btn btn--primary" :disabled="$store.state.auth.loggingIn">Log in</button>
            </div>

        </form>
        <section v-else>
            <h2>Hold on a sec</h2>
            <p>I've seen you haven't I?</p>
        </section>
    </page>
</template>

<script lang="ts">
    import Page from "./Page.vue";
    import Vue from "vue";

    export default Vue.extend({
        name: "LoginPage",
        components: {Page},
        data() {
            return {
                name: null,
                password: '',
                failed: false
            }
        },
        methods: {
            onSubmit() {
                if (this.$store.getters['auth/isAuthenticated']) {
                    this.$router.push('/');
                    return;
                }

                this.$store.dispatch('auth/attemptLogin',
                    {name: this.name, password: this.password})
                    .then(() => {
                        this.failed = false;
                        this.$store.dispatch('auth/updateUser')
                    })
                    .catch(() => this.failed = true)

            }
        },
        created(): void {
            this.name = this.$store.state.auth.lastLoginAttempt
        }
    })
</script>

<style scoped>

</style>
