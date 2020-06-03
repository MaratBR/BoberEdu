<template>
    <admin-section header="Teachers">
        <router-link class="button big" :to="{name: 'admin__teachers_new'}"><i class="fa fa-plus"></i> new</router-link>

        <teachers-search />
    </admin-section>
</template>

<script lang="ts">
    import {Vue, Component, dto, Prop, Watch} from "@common";
    import AdminStoreComponent from "@admin/components/AdminStoreComponent";
    import AdminSection from "@admin/components/layout/AdminSection.vue";
    import TeachersSearch from "@admin/components/teachers/TeachersSearch.vue";

    @Component({
        name: "TeachersList",
        components: {TeachersSearch, AdminSection}
    })
    export default class TeachersList extends AdminStoreComponent {}
</script>

<style scoped lang="scss">

</style>
