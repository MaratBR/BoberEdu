<template>
    <div class="notification notification--danger error">
        <error-presenter :error="error">
            <slot></slot>
        </error-presenter>
    </div>
</template>

<script lang="ts">
    import ErrorPresenter from "./ErrorPresenter.vue";
    import {Component, Prop, Vue} from "@common";

    @Component({
        components: {ErrorPresenter}
    })
    export default class Error extends Vue {
        @Prop() error: any
    }
</script>

<style scoped>

</style>
