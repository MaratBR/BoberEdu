<template>
    <button class="btn align-items-center justify-content-center"
            :disabled="disabled || state === 2"
            :class="['btn-' + (state === 1 ? savedType : mainType), 'd-flex' + (inline ? '-inline' : '')]"
            v-on="$listeners">
        <slot v-if="state === 0">
            {{ text }}
        </slot>
        <slot v-else-if="state === 1" name="saved">
            <i class="fas fa-check mr-1"></i>
            {{ savedText }}
        </slot>
        <slot name="saving" v-else-if="state === 2">
            <i class="spinner-border spinner-border-sm mr-1"></i>
            {{ savingText }}
        </slot>
    </button>
</template>

<script lang="ts">
    import {Vue, Component, Prop, Watch} from "@common";

    @Component({
        name: "SaveButton"
    })
    export default class SaveButton extends Vue {
        @Prop({type: Boolean}) saving: boolean;
        @Prop({type: Boolean}) inline: boolean;
        @Prop({type: Boolean}) disabled: boolean;
        @Prop({type: String}) type: string;
        @Prop({type: String, default: 'Saving...'}) savingText: string;
        @Prop({type: String, default: 'Saved'}) savedText: string;
        @Prop({type: String, default: 'Save'}) text: string;


        state = 0;

        get mainType() {
            return this.type || 'primary'
        }

        get savedType() {
            return this.type || 'success'
        }

        @Watch('saving')
        onSaving(newVal, oldVal) {
            if (newVal === oldVal)
                return;

            if (newVal)
                this.state = 2
            else {
                this.state = 1
                setTimeout(() => this.state = 0, 1000)
            }
        }
    }
</script>

<style scoped lang="scss">
    .btn {
        min-width: 130px;
        transition: .4s;
        margin: 4px;
    }
</style>
