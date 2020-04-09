<template>
    <div class="container">
        <slot></slot>
    </div>
</template>

<script lang="ts">
    import Vue from 'vue'
    import Component from "vue-class-component";

    @Component({
        name: 'Container'
    })
    export default class extends Vue {}
</script>

<style scoped>

</style>
