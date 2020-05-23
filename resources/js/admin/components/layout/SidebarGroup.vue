<template>
    <div class="s-group">
        <div class="s-group__header" @mouseover="">
            {{ header }}
        </div>

        <div class="s-group__children">
            <slot></slot>
        </div>
    </div>
</template>

<script lang="ts">
    import {Vue, Component, Prop} from "@common";

    @Component({
        name: "SidebarGroup"
    })
    export default class SidebarGroup extends Vue {
        @Prop() header: string;
    }
</script>

<style scoped lang="scss">
    .s-group {
        &__header {
            font-variant: all-petite-caps;
            font-size: 0.9em;
            padding: 4px 0 10px 4px;
            border-bottom: 1px solid #333;
            margin-bottom: 16px;
            color: #aaa;

            & > .fa {
                font-size: 0.9em;
            }
        }

        &__children {

        }


    }
</style>
