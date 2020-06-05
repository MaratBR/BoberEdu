<template>
    <admin-section header="Users">
        <user-search />
    </admin-section>
</template>

<script lang="ts">
    import {Vue, Component} from "@common";
    import AdminSection from "@admin/components/layout/AdminSection.vue";
    import UserSearch from "@admin/components/users/UserSearch.vue";

    @Component({
        name: "UsersList",
        components: {UserSearch, AdminSection}
    })
    export default class UsersList extends Vue {}
</script>

<style scoped lang="scss">

</style>
