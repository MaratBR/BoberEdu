<template>
    <router-link active-class="active" class="sb-link" :to="{name: 'admin__' + to}">
        <i class="fa" :class="'fa-' + icon"></i>
        {{ header }}
    </router-link>
</template>

<script lang="ts">
    import {Vue, Component, Prop} from "@common";

    @Component({
        name: "SidebarLink"
    })
    export default class SidebarLink extends Vue {
        @Prop({ required: true, type: String }) to: string;
        @Prop({ required: true, type: String }) header: string;
        @Prop({ type: String, default: null }) icon: string;
    }
</script>

<style scoped lang="scss">
    .sb-link {
        color: white;
        padding: 5px 7px;
        display: block;
        border-radius: 5px;
        text-decoration: none;
        margin-bottom: 5px;
        border: 2px solid transparent;

        &.active {
            background: rgba(white, 0.035);
        }

        &:hover {
            background: rgba(white, 0.055);
        }

        &:active {
            border-color: rgba(white, 0.4);
        }
    }
</style>
