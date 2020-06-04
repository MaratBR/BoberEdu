<template>
    <aside class="a-sidebar" ref="c">
        <admin-user-card />

        <scrollbar class="a-sidebar__children">
            <slot></slot>
        </scrollbar>
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
        height: 100%;
        color: #eee;
        display: flex;
        flex-direction: column;

        &__children {
            margin: 7px;
            overflow: auto;
        }

        .ss-scroll {
            background: rgba(white, 0.5);
        }
    }
</style>

