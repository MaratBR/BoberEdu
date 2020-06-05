<template>
    <page title="Login">
        <form class="form" @submit.prevent="onSubmit" v-if="!store.isAuthenticated">
            <input-text v-model="name" label="Login" />
            <input-text v-model="password" label="Password" type="password" />
            <div v-show="failed" class="notification notification--danger">Credentials doesn't match</div>

            <div class="control">
                <button class="btn btn--primary" :disabled="store.loggingIn">Log in</button>
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
    import {StoreComponent} from "@common/components/utils";
    import InputText from "@common/components/forms/InputText.vue";

    @Component({
        components: {InputText, Page}
    })
    export default class LoginPage extends StoreComponent {
        name = localStorage['lastLoginAttempt'] || '';
        password = '';
        failed = false;

        async onSubmit() {
            if (this.store.isAuthenticated) {
                await this.$router.push('/');
                return;
            }

            localStorage['lastLoginAttempt'] = this.name;

            try
            {
                await this.store.login({
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
