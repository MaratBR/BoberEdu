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
        position: relative;

        &.progress::after {
            content: '';
            background: repeating-linear-gradient(135deg, rgba(white, 0.1) 0, rgba(white, 0.1) 19px, rgba(blue, 0.2) 20px, rgba(blue, 0.2) 40px) fixed;
            display: block;
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;

            @keyframes bg {
                0% {
                    background-position-x: 0;
                }
                100% {
                    background-position-x: 40px * 1.41421356;
                }
            }

            animation: bg 1s infinite linear;
        }

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
