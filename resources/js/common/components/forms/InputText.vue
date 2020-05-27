<template>
    <div class="control">
        <label :for="id">{{ label }}</label>
        <input @input="$emit('input', $event.target.value)" :value="value" :type="type" :id="id" v-bind="$attrs" />
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

        @Prop() label: string;
        @Prop() value: string;
        @Prop({type: String, default: 'text'}) type: string;
    }
</script>

<style scoped lang="scss">
    label {
        display: block;
    }
</style>
