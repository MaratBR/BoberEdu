<template>
    <scrollbar class="a-sidebar" ref="c" scrollbar-color="rgba(255, 255, 255, 0.3)">
        <admin-user-card />

        <div class="a-sidebar__children">
            <slot></slot>
        </div>
    </scrollbar>
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
        height: 100%;
        color: #eee;
        overflow: hidden;

        &__children {
            margin: 7px 0 10px 7px;
        }

        .ss-scroll {
            background: rgba(white, 0.5);
        }
    }
</style>

