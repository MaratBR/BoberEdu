<template>

</template>

<script lang="ts">
    import * as marked from "marked";
    import * as DOMPurify from 'dompurify'
    import {Component, Prop, Vue, Watch} from "@common";
    import {inputId} from "@common/components/forms/utils";

    @Component
    export default class MarkdownEditor extends Vue {
        vertical: boolean = localStorage['md-editor-vmode'] === 'true';
        marked: string = '';
        id = inputId()

        @Prop({ type: String }) value: string;
        @Prop({ type: String }) label: string;

        onInput(val: string) {
            this.$emit('input', val)
        }

        toggleMode() {
            this.vertical = !this.vertical;
            localStorage['md-editor-vmode'] = this.vertical;
        }

        created(): void {
            if (this.value)
                this.marked = DOMPurify.sanitize(marked(this.value))
        }

        @Watch('value')
        valueChanged(newV) {
            if (newV)
                this.marked = DOMPurify.sanitize(marked(newV))
        }
    }
</script>

<style scoped lang="sass">
    .md-editor
        grid-template-columns: 1fr auto 1fr
        display: grid

        &.v
            grid-template-rows: 1fr auto 1fr
            grid-template-columns: 1fr

        &__separator
            padding: 0 2px



        &__toggleBtn
            transform: rotate(90deg)
            &.v
                transform: none
</style>
