<template>
    <div class="md-editor" :class="{'v': vertical}">
        <div class="md-editor__text">
            <textarea class="input" name="md" id="md-editor" cols="30" rows="10"
                      :value="value"
                      @input="onInput($event.target.value)"
            ></textarea>
        </div>

        <div class="md-editor__separator">
            <button @click="toggleMode()" class="btn btn--transparent md-editor__toggleBtn" :class="{'v': vertical}">
                <svg width="9" height="9">
                    <rect width="4" height="9"></rect>
                    <rect x="5" width="4" height="9"></rect>
                </svg>
            </button>
        </div>

        <div class="md-editor__preview">
            <div v-html="marked"></div>
        </div>
    </div>
</template>

<script lang="ts">
    import * as marked from "marked";
    import * as DOMPurify from 'dompurify'
    import {Component, Prop, Vue, Watch} from "vue-property-decorator";

    @Component
    export default class MarkdownEditor extends Vue {
        vertical: boolean = localStorage['md-editor-vmode'] === 'true';
        marked: string = '';

        @Prop({ type: String }) value: string;

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
