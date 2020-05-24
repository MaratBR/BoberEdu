<template>
    <section class="a-section" :class="{progress: inProgress}">
        <div class="a-section__header">
            <slot name="header">
                <h3>{{ header }}</h3>
            </slot>
        </div>

        <div class="a-section__body">
            <slot></slot>
        </div>
    </section>
</template>

<script lang="ts">
    import {Component, Prop} from "vue-property-decorator";
    import {Vue} from "@common";

    @Component
    export default class AdminSection extends Vue {
        @Prop({ default: '' }) header: string;
        @Prop({ default: false }) inProgress: boolean;
    }
</script>

<style scoped lang="scss">
    .a-section {
        background: white;
        border: 1px solid #eee;
        margin: 10px;
        border-radius: 4px;

        &__body {
            padding: 15px;
        }

        &__header {
            font-variant: all-petite-caps;
            font-size: 1.2em;
            border-bottom: 1px solid #eee;
            padding: 10px;
        }
    }
</style>
