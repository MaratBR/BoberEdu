<template>
    <admin-section header="Overview" :in-progress="inProgress">
        <div class="d-flex">
            <router-link class="text-decoration-none" :to="{name: 'admin__teachers_applications', query: {s: 'a'}}">
                <div class="card overview-card overview-card-hoverable" :class="{'border-danger': overview.teacherApplications.awaitingReview}">
                    <div class="card-body">
                        <h5 class="card-title">Teacher applications</h5>

                        <div class="display-4">{{ overview.teacherApplications.awaitingReview }}</div>
                        <span class="text-muted">awaiting review</span>
                    </div>
                </div>
            </router-link>

            <div class="card overview-card">
                <div class="card-body">
                    <h5 class="text-muted">Approved / Rejected:</h5>
                    <div class="display-4">
                        {{ overview.teacherApplications.approved }} / {{ overview.teacherApplications.rejected }}
                    </div>
                </div>
            </div>
        </div>
    </admin-section>
</template>

<script lang="ts">
    import {Vue, Component, dto, Watch} from "@common";
    import AdminSection from "@admin/components/layout/AdminSection.vue";
    import AdminStoreComponent from "@admin/components/AdminStoreComponent";

    @Component({
        name: "OverviewPage",
        components: {AdminSection}
    })
    export default class OverviewPage extends AdminStoreComponent {
        overview: dto.AdminOverviewDto = null
        inProgress = true

        async init() {
            this.overview = await this.admin.getOverview()
            this.inProgress = false
        }

        created() {
            this.init()
        }
    }
</script>

<style scoped lang="scss">
    .overview-card {
        text-decoration: none;
        color: black;
        margin: 2px 5px;

        &-hoverable {
            &:hover {
                border-width: 2px;
                margin: 1px 4px;
            }
        }
    }
</style>
