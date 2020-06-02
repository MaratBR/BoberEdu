<template>
    <div class="control">
        <label :for="id">{{ label }}</label>
        <input :aria-invalid="invalid ? 'true' : 'false'" @input="$emit('input', $event.target.value)" :value="value"
               :type="type" :id="id" :required="required" :disabled="$attrs.disabled || (!unlocked && protected)"
               @blur="validate" v-bind="$attrs" />
        <small v-show="protected && !unlocked">
            This control is temporarily disabled to avoid accident changes. <button @click.prevent="(unlocked = true) && $emit('unlocked')">Unlock?</button>
        </small><br>
        <small v-if="hint">{{hint}}</small>
    </div>
</template>

<script lang="ts">
    import {Vue, Component, Prop} from "@common";
    import {inputId} from "@common/components/forms/utils";

    @Component({
        name: "InputText"
    })
    export default class InputText extends Vue {
        id = inputId()
        unlocked = false;
        invalid = false

        validate() {
            console.log(this.$attrs)
            console.log(this.$props)
            if (this.min > -1 && this.value.length < this.min) {
                this.invalid = true
                return
            }

            if (this.max > -1 && this.value.length > this.max) {
                this.invalid = true
                return
            }

            if (this.required && (!this.value || this.value.trim() === '')) {
                this.invalid = true
                return
            }

            this.invalid = false
        }

        @Prop() label: string;
        @Prop() value: string;
        @Prop({type: String, default: 'text'}) type: string;
        @Prop({type: String}) hint: string;
        @Prop({type: Boolean, default: false}) protected: boolean;
        @Prop({type: Boolean, default: false}) required: boolean;
        @Prop({type: Number, default: -1}) min: number;
        @Prop({type: Number, default: -1}) max: number;
    }
</script>

<style scoped lang="scss">
    label {
        display: block;
    }
</style>
