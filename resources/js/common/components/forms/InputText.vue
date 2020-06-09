<template>
    <div class="form-group" :class="{row: inline}">
        <label :for="id" :class="{'col-sm-4': inline}">{{ label }}</label>
        <input :aria-invalid="invalid ? 'true' : 'false'" @input="$emit('input', $event.target.value)" :value="value"
               :type="type" :id="id" :required="required" :disabled="$attrs.disabled || (!unlocked && protected)"
               @blur="validate" v-bind="$attrs" class="form-control" :class="{'col-sm-8': inline}" />
        <template v-if="protected && !unlocked">
            <small class="form-text text-muted">
                This control is temporarily disabled to avoid accident changes. <button class="btn" @click.prevent="(unlocked = true) && $emit('unlocked')">Unlock?</button>
            </small><br>
        </template>
        <small v-if="hint" class="form-text text-muted">{{hint}}</small>
    </div>
</template>

<script lang="ts">
    import {Vue, Component, Prop} from "@common";
    import {inputId} from "@common/components/forms/utils";

    @Component({
        name: "InputText"
    })
    export default class InputText extends Vue {
        @Prop() validator: (v: any) => boolean
        @Prop({type: Boolean}) inline: boolean

        id = inputId()
        unlocked = false;
        invalid = false

        validate() {
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



            if (this.validator)
                this.invalid = this.validator(this.value)
            else
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
