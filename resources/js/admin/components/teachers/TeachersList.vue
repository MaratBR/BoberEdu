<template>
    <admin-section header="Teachers">
        <div class="form-group">
            <router-link class="btn btn-outline-secondary" :to="{name: 'admin__teachers_new'}"><i class="fas fa-plus"></i> new</router-link>
        </div>
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
