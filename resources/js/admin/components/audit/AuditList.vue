<template>
    <admin-section header="Audit log">
        <audit-view :pagination="pagination" @requestPage="(page = $event) && load()" />
    </admin-section>
</template>

<script lang="ts">
    import {Vue, Component, dto} from "@common";
    import DataPresenter from "@common/components/forms/DataPresenter.vue";
    import AdminStoreComponent from "@admin/components/AdminStoreComponent";
    import AuditView from "@admin/components/audit/AuditView.vue";
    import AdminSection from "@admin/components/layout/AdminSection.vue";

    @Component({
        name: "AuditList",
        components: {AdminSection, AuditView, DataPresenter}
    })
    export default class AuditList extends AdminStoreComponent {
        pagination: dto.PaginationDto<dto.AuditDto> = null
        page = 1

        async load() {
            this.pagination = null
            this.pagination = await this.admin.getAuditLog(this.page)
        }

        created() {
            this.load()
        }
    }
</script>

<style scoped lang="scss">

</style>
