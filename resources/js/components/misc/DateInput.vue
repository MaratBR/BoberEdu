<template>
    <input :data-date="previousDate" :aria-invalid="error" :placeholder="format" type="text" @input="onInput($event.target.value)">
</template>

<script lang="ts">

    import * as moment from "moment";
    import {Moment} from "moment";

    export default {
        name: "DateInput",
        methods: {
            onInput(value) {
                let m: Moment = moment.utc(value, this.format);
                this.error = !m.isValid();
                if (!this.error) {
                    let outFmt = this.outputFormat || this.format;
                    let r: Date | string = outFmt !== 'js' ? m.format(outFmt) : m.toDate();
                    this.previousDate = m.toDate();
                    this.$emit('input', r);
                }
                else
                    this.$emit('input', null);
            }
        },
        data() {
            return {
                error: false,
                previousDate: null
            }
        },
        props: {
            format: {
                type: String,
                default: 'YYYY-MM-DD'
            },
            outputFormat: {
                type: String,
                default: null
            }
        }
    }
</script>

<style scoped>

</style>
