<template>
    <page :title="user ? user.name : loading ? 'Loading...' : 'User not found'">
        <pre>
            {{user}}
        </pre>
        <error v-html="error" v-if="error" />
        <loader v-if="loading" />
        <section id="Profile" v-if="user">
            {{user.name}}
        </section>
    </page>
</template>

<script>
    import Page from "./Page.vue";
    import Loader from "../misc/Loader.vue";
    import Error from "../misc/Error.vue";
    import {User} from "../../store/modules/AuthModule";
    export default {
        name: "ProfilePage",
        components: {Error, Loader, Page},
        data() {
            return {
                user: null,
                loading: true,
                error: null
            }
        },
        computed: {
            username() {
                return this.user ? this.user.name : null
            }
        },
        watch: {
            $route(to) {
                this.init()
            }
        },
        created(): void {
        },
        methods: {
            init() {

            },
            updateFrom(promise: Promise<User>) {

            }
        }
    }
</script>

<style scoped>

</style>
