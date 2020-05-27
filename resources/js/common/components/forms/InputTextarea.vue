<template>
    <div class="control">
        <label :for="id">{{ label }}</label>
        <small class="hint" v-if="hint">{{ hint }}</small>
        <textarea @input="$emit('input', $event.target.value)" :value="value" :id="id" v-bind="$attrs" />
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

        @Prop() label: string;
        @Prop() hint: string;
        @Prop() value: string;
        @Prop({type: String, default: 'text'}) type: string;
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
