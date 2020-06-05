<template>
    <data-presenter
        searchable :pagination="pagination" @requestPage="(page = $event) && load()"
        @search="query = $event">

        <template v-slot:table-header>
            <th>#</th>
            <th>Name</th>
            <th>Trial<br><small>(days)</small></th>
            <th>Summary</th>
            <th>Info</th>
            <th>Available</th>
            <th>Purchasable</th>
        </template>

        <template v-slot="{summary, name, trialLength, id, requirements, available}">
            <td>{{ id }}</td>
            <td>
                <router-link :to="{name: 'admin__courses_edit', params: {id}}">{{ name }}</router-link>
            </td>
            <td>{{ trialLength || '–' }}</td>
            <td>{{ summary }}</td>
            <td>
                <span v-if="requirements.signUp.beg">{{ new Date(requirements.signUp.beg).toDateString() }} / {{ new Date(requirements.signUp.end).toDateString() }}</span>
            </td>
            <td>
                <bool-presenter :value="available" />
            </td>
            <td>
                <bool-presenter :value="requirements.signUp.purchasable" />
            </td>
        </template>

    </data-presenter>
</template>

<script lang="ts">
    import {Vue, Component, dto, Watch, Prop} from "@common";
    import DataPresenter from "@common/components/forms/DataPresenter.vue";
    import AdminStoreComponent from "@admin/components/AdminStoreComponent";
    import BoolPresenter from "@common/components/forms/BoolPresenter.vue";

    @Component({
        name: "CoursesSearch",
        components: {BoolPresenter, DataPresenter}
    })
    export default class CoursesSearch extends AdminStoreComponent {
        @Prop({type: Number}) categoryId: number;
        pagination: dto.PaginationDto<dto.CoursePageItemDto> = null
        page: number = 1
        query: string = null

        @Watch('query')
        @Watch('categoryId')
        async load() {
            this.pagination = null
            this.pagination = await this.admin.searchCourses({
                page: this.page, query: this.query, category: this.categoryId || undefined
            })
        }

        created() {
            this.load()
        }
    }
</script>

<style scoped lang="scss">

</style>
