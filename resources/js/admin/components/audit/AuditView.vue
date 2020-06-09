<template>
    <data-presenter :pagination="pagination" v-on="$listeners">
        <template v-slot:table-header>
            <th>#</th>
            <th>User</th>
            <th>Subject</th>
            <th>Action</th>
            <th>Comment</th>
            <th>Extra data</th>
        </template>

        <template v-slot="{id, user, sub, a, c, ua, ip, ex}">
            <td>{{ id }}</td>
            <td>
                <router-link :to="{name: 'profile', params: {id: user[0]}}">
                    {{ user[1] }}#{{ user[0] }}
                </router-link>
            </td>
            <td>
                Type: <code>{{ sub.type }}</code>,
                ID: <code>{{ sub.id }}</code>,
                display: <code>{{ sub.display }}</code>
            </td>
            <td>{{ a }}</td>
            <td>{{ c }}</td>
            <td><pre>{{ ex }}</pre></td>
        </template>
    </data-presenter>
</template>

<script lang="ts">
    import {Vue, Component, dto, Prop} from "@common";
    import DataPresenter from "@common/components/forms/DataPresenter.vue";

    @Component({
        name: "AuditView",
        components: {DataPresenter}
    })
    export default class AuditView extends Vue {
        @Prop() pagination: dto.PaginationDto<dto.AuditDto>
    }
</script>

<style scoped lang="scss">

</style>
