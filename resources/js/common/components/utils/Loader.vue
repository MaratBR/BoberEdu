<template>
    <div class="loader">
        <div class="loader__spinner">
            Loading...
        </div>
    </div>
</template>

<script lang="ts">
    import {Component, Vue} from "@common";

    @Component({
        name: 'Loader'
    })
    export default class Loader extends Vue {
    }
</script>

<style scoped lang="scss">
    .loading {
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
