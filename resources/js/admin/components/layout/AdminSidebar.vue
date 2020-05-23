<template>
    <aside class="a-sidebar">
        <admin-user-card />

        <div class="a-sidebar__children">
            <slot></slot>
        </div>
    </aside>
</template>

<script lang="ts">
    import {Vue, Component} from "@common";
    import AdminStoreComponent from "@admin/components/AdminStoreComponent";
    import AdminUserCard from "@admin/components/layout/AdminUserCard.vue";

    @Component({
        name: "AdminSidebar",
        components: {AdminUserCard}
    })
    export default class AdminSidebar extends AdminStoreComponent {

    }
</script>

<style scoped lang="scss">
    .a-sidebar {
        background: #262626;
        color: #eee;
        height: 100%;

        &__children {
            padding: 10px 7px;
        }
    }


</style>
