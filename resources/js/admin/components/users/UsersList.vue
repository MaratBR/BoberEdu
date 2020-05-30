<template>
    <admin-section header="Users">
        <pagination-control :pagination="pagination" @requestPage="page = $event">
            <template v-slot:body="{items}">
                <table>
                    <tr>
                        <th @click="order = 'id'">#</th>
                        <th @click="order = 'name'">Username</th>
                        <th @click="order = 'email'">E-Mail</th>
                        <th @click="order = 'displayName'">Display name</th>
                        <th>Avatar</th>
                    </tr>

                    <tr v-for="u in items">
                        <td>{{ u.id }}</td>
                        <td>
                            <router-link :to="{name: 'admin__users_edit', params: {id: u.id}}">{{ u.name }}</router-link>
                        </td>
                        <td>{{ u.email }}</td>
                        <td>{{ u.displayName }}</td>
                        <td>
                            <div class="avatar s30">
                                <img :src="'/storage/' + u.avatar" alt="">
                            </div>
                        </td>
                    </tr>
                </table>
            </template>
        </pagination-control>
    </admin-section>
</template>

<script lang="ts">
    import {Vue, Component, dto, Watch} from "@common";
    import PaginationControl from "@common/components/utils/PaginationControl.vue";
    import AdminSection from "@admin/components/layout/AdminSection.vue";
    import AdminStoreComponent from "@admin/components/AdminStoreComponent";

    @Component({
        name: "UsersList",
        components: {AdminSection, PaginationControl}
    })
    export default class UsersList extends AdminStoreComponent {
        pagination: dto.PaginationDto<dto.UserDto> = null
        page = 1;
        order = null;

        @Watch('page')
        @Watch('order')
        async load() {
            this.pagination = null
            this.pagination = await this.admin.paginateUsers({
                page: this.page,
                order: this.order
            })
        }

        created() {
            this.load()
        }
    }
</script>

<style scoped lang="scss">

</style>
