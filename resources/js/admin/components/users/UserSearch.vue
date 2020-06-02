<template>
    <data-presenter
        searchable :selectable="selectable" :pagination="pagination" @search="query = $event">
        <template v-slot:table-header>
            <th>#</th>
            <th>Username</th>
            <th>E-Mail</th>
            <th>Joined at</th>
            <th>Display name</th>
            <th>Avatar</th>
        </template>

        <template v-slot="{id, name, email, joinedAt, displayName, avatar}">
            <td>{{id}}</td>
            <td>{{name}}</td>
            <td>{{email}}</td>
            <td>{{joinedAt}}</td>
            <td>{{displayName}}</td>
            <td>
                <div class="avatar s30">
                    <img src="'/storage/' + avatar" alt="">
                </div>
            </td>
        </template>
    </data-presenter>
</template>

<script lang="ts">
    import {Vue, Component, dto, Watch, Prop} from "@common";
    import AdminStoreComponent from "@admin/components/AdminStoreComponent";
    import DataPresenter from "@common/components/forms/DataPresenter.vue";

    @Component({
        name: "UserSearch",
        components: {DataPresenter}
    })
    export default class UserSearch extends AdminStoreComponent {
        @Prop({type: Boolean, default: false}) selectable: boolean;

        pagination: dto.PaginationDto<dto.UserDto> = null
        page = 1;
        query = null;

        @Watch('page')
        @Watch('query')
        async load() {
            this.pagination = null
            this.pagination = await this.admin.searchUsers({
                page: this.page, query: this.query
            })
        }

        created() {
            this.load()
        }
    }
</script>

<style scoped lang="scss">

</style>
