<template>
    <div class="admin-panel">
        <div class="admin-panel__sidebar">
            <admin-sidebar>
                <sidebar-group header="Site">
                    <sidebar-link to="overview" header="Overview" icon="tachometer" />
                </sidebar-group>

                <sidebar-group header="Audit">
                    <sidebar-link to="audit" header="View log" icon="history" />
                </sidebar-group>

                <sidebar-group header="Users">
                    <sidebar-link to="users" header="All users" icon="list" />
                    <sidebar-link to="users_new" header="Register new user" icon="plus" />
                </sidebar-group>

                <sidebar-group header="Courses">
                    <sidebar-link to="courses" header="Courses" icon="list" />
                    <sidebar-link to="courses_new" header="Add new course" icon="plus" />
                    <sidebar-link to="categories" header="Categories" icon="list-alt" />
                </sidebar-group>

                <sidebar-group header="Teachers">
                    <sidebar-link to="teachers" header="Teachers" icon="list" />
                    <sidebar-link to="teachers_new" header="Register new teacher" icon="plus" />
                </sidebar-group>
            </admin-sidebar>
        </div>

        <div class="admin-panel__nav"></div>

        <scrollbar class="admin-panel__body">
            <keep-alive :max="5">
                <router-view></router-view>
            </keep-alive>
        </scrollbar>
    </div>
</template>

<script lang="ts">
    import {Component, Vue} from "vue-property-decorator";
    import AdminSidebar from "@admin/components/layout/AdminSidebar.vue";
    import SidebarGroup from "@admin/components/layout/SidebarGroup.vue";
    import SidebarLink from "@admin/components/layout/SidebarLink.vue";

    @Component({
        components: {SidebarLink, SidebarGroup, AdminSidebar}
    })
    export default class AdminPanel extends Vue {
    }
</script>

<style scoped lang="scss">
    .admin-panel {
        display: grid;
        grid-template: 50px 1fr / 300px 1fr;
        height: 100vh;

        &__sidebar {
            grid-area: 1 / 1 / 3 / 2;
            height: inherit;
        }

        &__body {
            background: #ededef;
            overflow: hidden;
            height: initial;
        }
    }
</style>
