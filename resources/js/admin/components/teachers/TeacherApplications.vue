<template>
    <admin-section header="Teacher applications">
        <div class="form-group">
            <select class="form-control" @input="setFilter">
                <option :value="null">All</option>
                <option value="r">Rejected</option>
                <option value="a">Approved</option>
                <option value="w">Need review</option>
            </select>
        </div>

        <data-presenter :pagination="pagination" @requestPage="(page = $event) && load()">
            <template v-slot:table-header>
                <th>#</th>
                <th>Sender</th>
                <th>Approved</th>
                <th></th>
            </template>

            <template v-slot="{id, user, approved, approvedBy}">
                <td>{{ id }}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <img :src="user.avatar" class="s60 img-thumbnail rounded-circle mr-1">
                        <span>{{user.name}}</span>
                    </div>
                </td>
                <td>
                    <span class="text-danger font-weight-bold" v-if="approved === null">Needs review</span>
                    <span class="text-success" v-else-if="approved === true">Approved</span>
                    <span class="text-secondary" v-if="approved === false">Rejected</span>
                </td>
                <td>
                    <router-link class="btn" :class="approved === null ? 'btn-primary' : 'btn-outlined-secondary'" :to="{name: 'teachers_application', params: {id}}">{{ approved === null ? 'Review' : 'View' }}</router-link>
                </td>
            </template>
        </data-presenter>
    </admin-section>
</template>

<script lang="ts">
    import {Vue, Component, dto, Prop, Watch} from "@common";
    import AdminSection from "@admin/components/layout/AdminSection.vue";
    import DataPresenter from "@common/components/forms/DataPresenter.vue";
    import AdminStoreComponent from "@admin/components/AdminStoreComponent";

    @Component({
        name: "TeacherApplications",
        components: {DataPresenter, AdminSection}
    })
    export default class TeacherApplications extends AdminStoreComponent {
        pagination: dto.PaginationDto<dto.TeacherApprovalForm> = null
        page = 1
        @Prop({type: String, default: null}) approvedFilter;

        @Watch('approvedFilter')
        async load() {
            this.pagination = null
            this.pagination = await this.admin.getTeacherApplications({
                page: this.page, f: this.approvedFilter
            })
        }

        setFilter(f) {
            this.$router.replace({
                name: 'admin__teachers_applications',
                query: {f: f.target.value}
            })
        }

        created() {
            this.load()
        }
    }
</script>

<style scoped lang="scss">

</style>
