<template>
    <div>
        <p>Playground</p>

        <form action="">
            <input type="button">
            <input type="checkbox">
            <input type="color">
            <input type="date">
            <input type="datetime-local">
            <input type="email">
            <input type="file">
            <input type="hidden">
            <input type="image">
            <input type="month">
            <input type="number">
            <input type="password">
            <input type="radio">
            <input type="range">
            <input type="reset">
            <input type="search">
            <input type="submit">
            <input type="tel">
            <input type="text">
            <input type="time">
            <input type="url">
            <input type="week">
        </form>
    </div>
</template>

<script lang="ts">
    import {Vue, Component} from "@common";

    @Component({
        name: "Playground"
    })
    export default class Playground extends Vue {

    }
</script>

<style scoped lang="scss">
    input {
        display: block;
        margin: 30px;
    }

</style>
