<template>
    <div class="error__body" v-if="typeof error === 'undefined'">
        <slot></slot>
    </div>
    <div class="error__body" v-else>
        <span v-if="typeof error !== 'object'">{{error}}</span>
        <template v-else-if="error instanceof Array">
            <error-presenter v-if="error.length === 1" :error="error[0]" />
            <ul v-else>
                <li v-for="err in error">
                    <error-presenter :error="err" />
                </li>
            </ul>
        </template>
        <template v-else-if="typeof error.message !== 'undefined'">
            <b>{{error.message}}</b>
            <error-presenter v-if="typeof error.errors === 'object'" :error="error.errors" />
        </template>
        <template v-else>
            <ul>
                <li v-for="(v, k) in error">
                    <b>{{k}}</b>: <error-presenter :error="v" />
                </li>
            </ul>
        </template>
    </div>
</template>

<script lang="ts">
    import {Component, Prop, Vue} from "vue-property-decorator";

    @Component({
        name: 'ErrorPresenter'
    })
    export default class ErrorPresenter extends Vue {
        @Prop() error: any
    }
</script>

<style scoped>

</style>
