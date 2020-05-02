<template>
    <div class="md-viewer" v-html="parsed"></div>
</template>

<script lang="ts">
    import Component from "vue-class-component";
    import {Prop, Vue, Watch} from "vue-property-decorator";
    import * as DOMPurify from "dompurify";
    import * as marked from 'marked';

    @Component
    export default class MarkdownViewer extends Vue {
        @Prop({ default: 'cake is a lie' }) value: string;
        parsed: string;

        data() {
            return {
                parsed: ''
            }
        }

        parse() {
            this.parsed = this.value ? DOMPurify.sanitize(marked(this.value, {
                headerPrefix: 'Header_'
            })) : '';
        }

        created() {
            this.parse()
        }

        @Watch('value')
        onChange() {
            this.parse()
        }
    }
</script>

<style scoped>

</style>
