<template>
    <div class="notification notification--danger error">
        <error-presenter :error="error">
            <slot></slot>
        </error-presenter>
    </div>
</template>

<script lang="ts">
    import ErrorPresenter from "./ErrorPresenter.vue";
    export default {
        name: "Error",
        components: {ErrorPresenter},
        props: ['error']
    }
</script>

<style scoped>

</style>
