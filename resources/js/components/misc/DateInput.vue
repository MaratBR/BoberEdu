<template>
    <input :data-date="previousDate" :aria-invalid="error" :placeholder="format" type="text" @input="onInput($event.target.value)">
</template>

<script lang="ts">
    import {Prop, Vue} from "vue-property-decorator";

    export default class DateInput extends Vue {
        onInput(value) {
            /**
             * let m: Moment = moment.utc(value, this.format);
             this.error = !m.isValid();
             if (!this.error) {
                let outFmt = this.outputFormat || this.format;
                let r: Date | string = outFmt !== 'js' ? m.format(outFmt) : m.toDate();
                this.previousDate = m.toDate();
                this.$emit('input', r);
            }
             else
             this.$emit('input', null);
             */
        }
        error = false;
        previousDate: string = null;

        @Prop({ default: 'YYYY-MM-DD' }) format!: string;
        @Prop({ default: null }) outputFormat: string;
    }
</script>

<style scoped>

</style>
