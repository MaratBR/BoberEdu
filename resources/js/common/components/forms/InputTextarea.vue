<template>
    <div class="control">
        <label :for="id">{{ label }}</label>
        <textarea @input="$emit('input', $event.target.value)" :value="value" :id="id" :required="required"
                  :aria-invalid="invalid ? 'true' : 'false'" v-bind="$attrs" @blur="validate" />
        <small class="hint" v-if="hint">{{ hint }}</small>
    </div>
</template>

<script lang="ts">
    import {Vue, Component, Prop} from "@common";
    import {inputId} from "@common/components/forms/utils";

    @Component({
        name: "InputTextarea"
    })
    export default class InputTextarea extends Vue {
        id = inputId()
        invalid = false

        validate() {
            this.invalid = this.required && (!this.value || this.value.trim() === '');
        }

        @Prop() label: string;
        @Prop() hint: string;
        @Prop() value: string;
        @Prop({type: String, default: 'text'}) type: string;
        @Prop({type: Boolean, default: false}) required: string;
    }
</script>

<style scoped lang="scss">
    label {
        display: block;
    }

    .hint {
        color: #555;
    }
</style>
