<template>
    <section class="a-section">
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

    @Component
    export default class AdminSection extends Vue {
        @Prop({ type: String }) header: string;
    }
</script>

<style scoped lang="scss">

</style>
