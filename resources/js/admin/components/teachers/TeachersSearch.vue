<template>
    <data-presenter searchable :filter="filter" :selectable="selectable" :pagination="pagination"
                    @requestPage="page = $event" v-on="$listeners"
        @search="search">
        <template v-slot:table-header>
            <th>#</th>
            <th>Avatar</th>
            <th>Full name</th>
            <th></th>
        </template>

        <template v-slot="{id, avatar, fullName}">
            <td>{{id}}</td>
            <td>
                <img class="img-thumbnail rounded-circle s60" :src="avatar" alt="">
            </td>
            <td>{{ fullName }}</td>
            <td>
                <router-link :to="{name: 'admin__teachers_edit', params:{id: id}}" class="btn">
                    <i class="fas fa-edit"></i>
                    edit
                </router-link>
            </td>
        </template>
    </data-presenter>
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
        @Prop() filter;

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

        created() {
            this.load()
        }
    }
</script>

<style scoped lang="scss">

</style>
