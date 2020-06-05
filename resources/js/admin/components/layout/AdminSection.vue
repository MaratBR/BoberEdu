<template>
    <section class="a-section" :class="{hidden}">
        <div class="a-section__header" v-if="!notFound && ($slots.header || header)">
            <div class="a-section__header__body">
                <slot name="header" v-if="!inProgress">
                    <ul class="breadcrumb breadcrumb-clear">
                        <li class="breadcrumb-item active">{{ header }}</li>
                    </ul>
                </slot>
                <div class="pulse s1" v-else></div>
            </div>

            <div class="chevron" @click.prevent="hidden = !hidden">
                <i class="fas fa-chevron-up"></i>
            </div>
        </div>

        <div class="a-section__body-wrapper">
            <div class="a-section__body">
                <slot v-if="!inProgress"></slot>
                <not-found v-else-if="notFound" />
                <div v-else>
                    <div class="pulse s1" v-for="_ in 6"></div>

                    <br><br><br>

                    <div class="pulse pulse--button"></div>
                </div>
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
        @Prop({ default: true }) spoiler: boolean;

        hidden = false;
    }
</script>

<style scoped lang="scss">
    .a-section {
        background: white;
        border: 1px solid #eee;
        margin: 10px;
        border-radius: 4px;
        position: relative;

        &.hidden &__body-wrapper {
            max-height: 0;
        }

        &.hidden .chevron {
            max-height: 0;
            transform: rotate(180deg);
        }

        &__body {
            padding: 15px;
        }

        &__body-wrapper {
            max-height: 10000px;
            transition: .3s;
            overflow: hidden;
        }

        &__header {
            font-variant: all-petite-caps;
            font-size: 1.2em;
            border-bottom: 1px solid #eee;
            padding: 10px;
            display: flex;

            &__body {
                flex-grow: 1;
            }

            & > .chevron {
                align-self: center;
                margin-right: 15px;
                height: 35px;
                width: 35px;
                border-radius: 1000px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;

                &:hover {
                    background: rgba(black, 0.05);
                }
            }
        }
    }
</style>
