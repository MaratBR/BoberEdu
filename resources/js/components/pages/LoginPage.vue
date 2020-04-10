<template>
    <page title="Login">
        <form class="form" @submit.prevent="onSubmit" v-if="!store.auth.isAuthenticated">
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
                <button class="btn btn--primary" :disabled="store.auth.loggingIn">Log in</button>
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
    import {Component, Vue} from "vue-property-decorator";
    import {Store} from "../../store";
    import {useStore} from "vuex-simple";

    @Component({
        components: {Page}
    })
    export default class LoginPage extends Vue {
        name = localStorage['lastLoginAttempt'] || '';
        password = '';
        failed = false;
        store: Store = useStore(this.$store);

        async onSubmit() {
            if (this.store.auth.isAuthenticated) {
                await this.$router.push('/');
                return;
            }

            localStorage['lastLoginAttempt'] = this.name;

            try
            {
                await this.store.auth.login({
                    name: this.name,
                    password: this.password
                });
            }
            catch (e)
            {
                this.failed = true;
                return;
            }

            this.failed = false;
            if (this.$route.query.next) {
                let next = this.$route.query.next instanceof Array ? this.$route.query.next[0] : this.$route.query.next;
                await this.$router.push(next)
            } else {
                await this.$router.push('/')
            }
        }
    }
</script>

<style scoped>

</style>
