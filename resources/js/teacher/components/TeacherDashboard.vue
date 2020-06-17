<template>
    <loader v-if="loading" />
    <div class="container" v-else>
        <h4>Income</h4>
        <div class="d-flex">
            <div class="card">
                <div class="card-body">
                    <h2 class="display-4">$ {{ dashboard.income }}</h2>
                    <span>total</span>
                </div>
            </div>
        </div>

        <h4 class="mt-3">Your courses</h4>
        <div class="courses">
            <p v-if="dashboard.courses.length === 0">
                You don't have any course yet
            </p>
            <div class="d-flex" v-for="c in dashboard.courses" :key="c.id">
                <course-wide-card :course="c" class="flex-grow-1 mr-2" />
                <router-link :to="{name: 'teacher_dashboard__edit', params: {id: c.id}}" class="btn btn-light align-self-center course-manage">
                    <i class="fas fa-cogs fa-2x"></i><br>
                    <span>Manage</span>
                </router-link>
            </div>
            <p>
                <router-link :to="{name: 'teacher_dashboard__new'}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Create course
                </router-link>
            </p>
        </div>
    </div>
</template>

<script lang="ts">
    import {Vue, Component, dto} from "@common";
    import TeachersStoreComponent from "@app/teacher/components/TeachersStoreComponent";
    import CourseWideCard from "@common/components/courses/CourseWideCard.vue";
    import NotFound from "@common/components/pages/NotFound.vue";
    import Loader from "@common/components/utils/Loader.vue";

    @Component({
        name: "TeacherDashboard",
        components: {Loader, NotFound, CourseWideCard}
    })
    export default class TeacherDashboard extends TeachersStoreComponent {
        dashboard: dto.TeacherDashboardDto = null;
        loading = true

        async load() {
            this.dashboard = await this.teacher.getDashboard()
            this.loading = false
        }

        created() {
            this.load()
        }
    }
</script>

<style scoped lang="scss">
</style>
