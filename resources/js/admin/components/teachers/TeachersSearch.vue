<template>
    <data-presenter searchable :selectable="selectable" :pagination="pagination" @requestPage="page = $event"
        @search="search" />
</template>

<script lang="ts">
    import {Vue, Component, Prop, dto, Watch} from "@common";
    import DataPresenter from "@common/components/forms/DataPresenter.vue";
    import AdminStoreComponent from "@admin/components/AdminStoreComponent";

    @Component({
        name: "TeachersSearch",
        components: {DataPresenter}
    })
    export default class TeachersSearch extends AdminStoreComponent {
        @Prop({default: Boolean}) selectable: boolean;

        pagination: dto.PaginationDto<dto.TeacherDto> = null
        page = 1
        query = null

        @Watch('page')
        async load() {
            this.pagination = null
            this.pagination = await this.admin.searchTeachers({
                page: this.page,
                query: this.query
            })
        }

        search(query: string) {
            this.page = 1
            this.query = query
            return this.load()
        }
    }
</script>

<style scoped lang="scss">

</style>
