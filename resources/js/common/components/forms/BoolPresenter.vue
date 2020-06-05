<template functional>
    <i class="fas" :class="props.value ? 'fa-check-circle' : 'fa-times-circle'" aria-hidden="true"></i>
</template>

<script lang="ts">
    import {Vue, Component, Prop} from "@common";

    @Component({
        name: "BoolPresenter"
    })
    export default class BoolPresenter extends Vue {
        @Prop({ type: Boolean }) value: boolean;
    }
</script>

<style scoped lang="scss">
    i {
        font-size: 1.4em;
    }

    .fa-check-circle {
        color: green;
    }

    .fa-times-circle {
        color: red;
    }
</style>
