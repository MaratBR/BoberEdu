<template>
    <container>
        <section class="page">
            <header class="page__header">
                <h2>{{title}}</h2>
                <div class="page__header__right"></div>
            </header>
            <hr>
            <main class="page__content">
                <slot></slot>
            </main>
        </section>
    </container>
</template>

<script>
    import Container from "../Container";
    export default {
        name: "Page",
        components: {Container},
        props: {
            title: String
        }
    }
</script>

<style scoped>

</style>
