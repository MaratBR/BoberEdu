<template>
    <div class="admin-panel">
        <div class="admin-panel__sidebar">
            <div class="sidebar-menu">
                <div class="item" v-for="item in panelMap" :data-item-name="item.name">
                    <button @click="loadComponent(item.component)" class="item-name">{{ item.display }}</button>
                    <div class="item__children" v-if="item.children">
                        <div class="item-sub" v-for="sub in item.children" :data-item-name="item.name + '.' + sub.name">
                            <button class="item-name" @click="loadComponent(sub.component)">{{ sub.display }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-panel__body">
            <data-table :columns="['a', 'b', 'c']" :data="exampleData"></data-table>
            <keep-alive max="10">
                <component :is="bodyComponent" />
            </keep-alive>
        </div>
    </div>
</template>

<script lang="ts">
    import {Component, Vue} from "vue-property-decorator";
    import map from "./admin-panel-map";
    import DataTable from "./DataTable.vue";
    @Component({
        components: {DataTable}
    })
    export default class extends Vue {
        panelMap = map;
        bodyComponent = null;

        exampleData = [
            {
                a: 3,
                b: true,
                c: "Hi"
            },
            {
                a: 3,
                b: true,
                c: "Hi"
            },
            {
                a: 3,
                b: true,
                c: "Hi"
            },
            {
                a: 3,
                b: true,
                c: "Hi"
            }
        ];

        async loadComponent(componentPromise) {
            this.bodyComponent = (await componentPromise).default;
        }
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
