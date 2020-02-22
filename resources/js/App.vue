<template>
    <div id="AppContainer">
        <navbar />
        <div id="ViewContainer">
            <router-view />
        </div>
    </div>
</template>

<script>
    import Navbar from "./components/Navbar";
    export default {
        name: "App",
        components: {Navbar}
    }
</script>

<style scoped>

</style>
