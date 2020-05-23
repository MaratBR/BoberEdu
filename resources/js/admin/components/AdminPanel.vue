<template>
    <div class="admin-panel">
        <div class="admin-panel__sidebar">
            <admin-sidebar>
                <sidebar-group header="Courses">
                    <sidebar-link to="categories" header="Categories" icon="list-alt" />
                    <sidebar-link to="categories" header="Categories" icon="list-alt" />
                    <sidebar-link to="categories" header="Categories" icon="list-alt" />
                    <sidebar-link to="categories" header="Categories" icon="list-alt" />
                </sidebar-group>
            </admin-sidebar>
        </div>

        <div class="admin-panel__nav"></div>

        <div class="admin-panel__body">
            <router-view></router-view>
        </div>
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
        }

        &__body {
            background: #ededef;
        }
    }
</style>
