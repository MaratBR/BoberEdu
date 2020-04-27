<template>
    <div class="tabs">
        <div class="tabs__headers">
            <button @click="setTab(i)" class="tabs__header" :class="{'tabs__header--active': selected === i}"
                    v-for="(tab, i) in tabs">
                {{ tab.name }}
            </button>
        </div>

        <div class="tabs__body">
            <slot></slot>
        </div>
    </div>
</template>

<script lang="ts">
    import {Component, Vue} from "vue-property-decorator";
    import Tab from "./Tab.vue";

    @Component
    export default class Tabs extends Vue {
        tabs: (Tab & any)[] = [];
        selected: number = null;

        mounted() {
            this.tabs = this.$children.filter(child => child instanceof Vue && child.constructor.name === 'Tab');
            this.setTab(0)
        }

        setTab(index: number) {
            if (this.selected !== null) {
                this.tabs[this.selected].active = false;
            }

            this.selected = index;

            if (this.selected !== null) {
                this.tabs[this.selected].active = true;
            }
        }
    }
</script>

<style scoped lang="scss">
    @import "../../sass/lib/config";

    .tabs {
        &__body {

        }

        &__headers {
            display: flex;
            margin-bottom: 40px;
            border-bottom: 1px solid #ddd;
        }

        &__header {
            display: inline-block;
            background: none;
            font-size: 17px;
            cursor: pointer;
            padding: 10px 20px;
            position: relative;
            color: #555;
            border-style: solid;
            border-color: #555;
            border-width: 0 0 1px 0;
            top: 1px;
            outline: none;

            &:focus {
                outline: 1px dashed #999;
            }

            &--active {
                border-color: $ss-primary;
                color: $ss-primary;
                background: #fafafa;
            }

        }
    }
</style>
