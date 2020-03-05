<template>
    <div class="notification notification--danger error">
        <slot v-once v-if="typeof error === 'undefined'"></slot>
        <template v-once v-else>
            <span v-if="typeof error !== 'object'">{{error}}</span>
            <ul v-else-if="error instanceof Array">
                <li v-for="err in error">
                    <error :error="err" />
                </li>
            </ul>
            <template v-else-if="typeof error.message !== 'undefined'">
                <b>{{error.message}}</b>
                <error v-if="typeof error.errors === 'object'" :error="error.errors" />
            </template>
            <template v-else>
                <ul>
                    <li v-for="(v, k) in error">
                        <b>{{k}}</b>: {{v}}
                    </li>
                </ul>
            </template>
        </template>
    </div>
</template>

<script lang="ts">
    export default {
        name: "Error",
        props: ['error']
    }
</script>

<style scoped>

</style>
