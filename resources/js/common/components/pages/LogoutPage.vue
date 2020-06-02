<template>
    <page title="Logout">
        <router-link :to="{name: 'logout', query: {token: store.logoutToken}}">
            Click here to log out
        </router-link>
    </page>
</template>

<script lang="ts">
    import {Vue, Component, Watch} from "@common";
    import Page from "@common/components/pages/Page.vue";
    import {StoreComponent} from "@common/components/utils";

    @Component({
        name: "LogoutPage",
        components: {Page}
    })
    export default class LogoutPage extends StoreComponent {
        @Watch('$route')
        async logout() {
            if (this.$route.query.token && this.store.logoutToken === this.$route.query.token) {
                await this.store.logout()
                await this.$router.push('/')
            }
        }

        created() {
            this.logout()
        }
    }
</script>

<style scoped lang="scss">

</style>
