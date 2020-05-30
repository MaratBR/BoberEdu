<template>
    <section class="a-section">
        <div class="a-section__header" v-if="!notFound && ($slots.header || header)">
            <slot name="header" v-if="!inProgress">
                <h3>{{ header }}</h3>
            </slot>
            <div class="pulse s1" v-else></div>
        </div>

        <div class="a-section__body">
            <slot v-if="!inProgress"></slot>
            <not-found v-else-if="notFound" />
            <div v-else>
                <div class="pulse s1" v-for="_ in 6"></div>

                <br><br><br>

                <div class="pulse pulse--button"></div>
            </div>
        </div>
    </section>
</template>

<script lang="ts">
    import {Component, Prop} from "vue-property-decorator";
    import {Vue} from "@common";
    import NotFound from "@common/components/pages/NotFound.vue";
    @Component({
        components: {NotFound}
    })
    export default class AdminSection extends Vue {
        @Prop({ default: '' }) header: string;
        @Prop({ default: false }) inProgress: boolean;
        @Prop({ default: false }) notFound: boolean;
    }
</script>

<style scoped lang="scss">
    .a-section {
        background: white;
        border: 1px solid #eee;
        margin: 10px;
        border-radius: 4px;
        position: relative;

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
