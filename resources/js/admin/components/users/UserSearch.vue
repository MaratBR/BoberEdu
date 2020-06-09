<template>
    <data-presenter @requestPage="(page = $event) && load()" searchable :selectable="selectable"
                    :pagination="pagination" @search="query = $event" :filter="filter" v-on="$listeners">
        <template v-slot:table-header>
            <th>#</th>
            <th>Username</th>
            <th>E-Mail</th>
            <th>Joined at</th>
            <th>Display name</th>
            <th>Avatar</th>
            <th></th>
        </template>

        <template v-slot="{id, name, email, joinedAt, displayName, avatar}">
            <td>{{id}}</td>
            <td>{{name}}</td>
            <td>{{email}}</td>
            <td>{{new Date(joinedAt).toDateString()}}</td>
            <td>{{displayName}}</td>
            <td>
                <img class="img-thumbnail rounded-circle s60" :src="avatar" alt="">
            </td>
            <td>
                <router-link :to="{name: 'admin__users_edit', params: {id}}"></router-link>
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
        @Prop() filter: (u: dto.AdminUserDto) => boolean;

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
