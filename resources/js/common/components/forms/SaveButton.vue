<template>
    <button class="btn d-flex align-items-center justify-content-center"
            :disabled="state === 2" :class="state === 1 ? 'btn-success' : 'btn-primary'">
        <slot v-if="state === 0">
            Save
        </slot>
        <slot v-else-if="state === 1">
            <i class="fas fa-check mr-1"></i>
            Saved
        </slot>
        <slot name="saving" v-else-if="state === 2">
            <i class="spinner-border spinner-border-sm mr-1"></i>
            Saving...
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
        state = 0;

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
    }
</style>
