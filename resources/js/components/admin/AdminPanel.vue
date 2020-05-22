<template>
    <div class="admin-panel">
        <div class="admin-panel__sidebar">

        </div>

        <div class="admin-panel__body">
            <router-view></router-view>
        </div>
    </div>
</template>

<script lang="ts">
    import {Component, Vue} from "vue-property-decorator";

    @Component
    export default class AdminPanel extends Vue {
    }
</script>

<style scoped lang="scss">
    .admin-panel {
        display: grid;
        grid-template-columns: 300px 1fr;
        min-height: 500px;
    }

    .sidebar-menu {
        border-right: 1px solid #eee;
        height: 100%;

        button {
            background: transparent;
            border: none;
            text-align: left;
            padding: 6px 10px;
            width: 100%;
            cursor: pointer;

            &:hover {
                outline-offset: -1px;
                outline: 1px dotted #666;
            }
        }

        .item {
            & > .item-name {
                width: 100%;
                border: 1px solid #f9f9f9;
                font-size: 1.2em;
                background: #f9f9f9;
            }

            .item-sub {
                margin-left: 20px;
            }
        }


    }
</style>
