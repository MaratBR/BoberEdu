<template>
    <div class="d-flex justify-content-center align-items-center p-5">
        <div class="spinner-grow m-1" role="status" v-for="_ in 5">
            <span class="sr-only">Loading...</span>
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
    @for $i from 1 to 6 {
        .spinner-grow:nth-child(#{$i}) {
            animation-delay: 0.16s * $i;
        }
    }
</style>
