<template>
    <div class="md-viewer" v-html="parsed"></div>
</template>

<script>
    import * as DOMPurify from 'dompurify'
    import * as marked from 'marked'

    export default {
        name: "MarkdownViewer",
        props: ['value'],
        data() {
            return {
                parsed: ''
            }
        },
        methods: {
            parse() {
                this.parsed = this.value ? DOMPurify.sanitize(marked(this.value)) : '';
            }
        },
        created() {
            this.parse()
        }
    }
</script>

<style scoped>

</style>
