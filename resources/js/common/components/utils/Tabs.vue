<template>
    <div :class="styleClass">
        <div :class="styleClass + '__headers'">
            <button @click="setTab(i)" :class="[styleClass + '__header', {active: selected === i}]"
                    v-for="(tab, i) in tabs">
                {{ tab.name }}
            </button>
        </div>

        <div :class="styleClass + '__body'">
            <slot></slot>
        </div>
    </div>
</template>

<script lang="ts">
    import {Component, Prop, Vue} from "vue-property-decorator";

    @Component
    export default class Tabs extends Vue {
        @Prop({ default: 'tabs' }) styleClass: string;
        tabs: any[] = [];
        selected: number = null;

        mounted() {
            this.tabs = this.$children.filter(child => child instanceof Vue && child.$options.name === 'Tab');
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
    @import "sass/_config";


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

            &.active {
                border-color: $ss-primary;
                color: $ss-primary;
                background: #fafafa;
            }

        }
    }

    .sidebar {
        display: flex;

        &__body {
            padding-top: 50px;
            flex-grow: 1;
        }


        &__headers {
            min-width: 200px;
            display: flex;
            align-items: stretch;
            margin-right: 20px;
            flex-direction: column;
            border-right: 1px solid #ddd;
            min-height: 300px;
        }

        &__header {
            cursor: pointer;
            padding: 15px 0;
            background: white;
            text-align: center;
            width: 100%;
            margin: 5px 0;
            border-width: 0 5px 0 0;
            border-color: transparent;
            transition: .2s;
            outline: none;

            &.active, &:hover {
                background: #f9f9f9;
                border-color: $ss-primary;
            }
        }
    }
</style>
