<template>
    <div class="control">
        <label>
            <input type="checkbox" :checked="value" @input="$emit('input', $event.target.checked)">
            {{ label }}
        </label><br>
        <small v-if="hint">{{ hint }}</small>
    </div>
</template>

<script lang="ts">
    import {Vue, Component, Prop} from "@common";

    @Component({
        name: "InputCheckbox"
    })
    export default class InputCheckbox extends Vue {
        @Prop({type: String}) hint: string;
        @Prop({type: Boolean, default: false}) value: boolean;
        @Prop({type: String}) label: string;
    }
</script>

<style scoped lang="scss">

</style>
