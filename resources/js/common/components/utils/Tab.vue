<template>
    <div class="tab" :class="{'tab--active': active}">
        <slot></slot>
    </div>
</template>

<script lang="ts">
    import {Component, Prop, Vue} from "vue-property-decorator";

    @Component({
        name: 'Tab'
    })
    export default class Tab extends Vue {
        public active: boolean = false;
        @Prop({ required: true })
        name: string;

        public get tabName() {
            return this.name
        }
    }
</script>

<style scoped lang="scss">
    .tab {
        &--active {
            display: inherit;
        }

        display: none;
    }
</style>
