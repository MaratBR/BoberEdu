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

<script lang="ts">
    import Page from "./Page.vue";
    import {api, auth, log, users} from "../../api";
    import Loader from "../misc/Loader.vue";
    import Error from "../misc/Error.vue";
    import {User} from "../../models";
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
            api.ready().then(this.init.bind(this))
        },
        methods: {
            init() {
                let id: number = +this.$route.params.id;
                if (isNaN(id)) {
                    this.error = `Invalid user id: ${this.$route.params.id}`
                } else {
                    this.error = null;
                    let promise = (this.$store.getters.isAuthenticated && id === this.$store.state.auth.user.id) ?
                        this.$store.dispatch('updateUser') : users.get(id);
                    console.log(promise)
                    this.updateFrom(promise)
                }
            },
            updateFrom(promise: Promise<User>) {
                promise
                    .then(user => {
                        this.user = user
                    })
                    .catch(err => {
                        this.error = err.toString()
                        throw err
                    })
                    .finally(() => this.loading = false)
            }
        }
    }
</script>

<style scoped>

</style>
