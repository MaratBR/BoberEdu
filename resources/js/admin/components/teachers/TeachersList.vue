<template>
    <admin-section header="Teachers">
        <router-link class="button big" :to="{name: 'admin__teachers_new'}"><i class="fa fa-plus"></i> new</router-link>

        <pagination-control :pagination="teachers" @requestPage="$router.push({name: 'admin__teachers', query: {p: $event}})">
            <template v-slot:body="{items}">
                <table>
                    <tr>
                        <th>#</th>
                        <th>Full name</th>
                        <th>Links</th>
                    </tr>
                    <tr v-for="t in items">
                        <td>{{ t.id }}</td>
                        <td>{{ t.fullName }}</td>
                        <td>
                            <router-link class="button"
                                :to="{name: 'admin__teachers_edit', params: {id: t.id}}"><i class="fa fa-edit"></i>edit</router-link>
                        </td>
                    </tr>
                </table>
            </template>
        </pagination-control>
    </admin-section>
</template>

<script lang="ts">
    import {Vue, Component, dto, Prop, Watch} from "@common";
    import AdminStoreComponent from "@admin/components/AdminStoreComponent";
    import AdminSection from "@admin/components/layout/AdminSection.vue";
    import PaginationControl from "@common/components/utils/PaginationControl.vue";

    @Component({
        name: "TeachersList",
        components: {PaginationControl, AdminSection}
    })
    export default class TeachersList extends AdminStoreComponent {
        teachers: dto.PaginationDto<dto.TeacherDto> = null
        @Prop({ default: 1 }) page: number;

        @Watch('page')
        async load() {
            this.teachers = null
            this.teachers = await this.admin.teachers.paginate(this.page)
        }

        created() {
            this.load()
        }
    }
</script>

<style scoped lang="scss">

</style>
