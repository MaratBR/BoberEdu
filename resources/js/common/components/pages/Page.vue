<template>
    <div class="container">
        <section class="page">
            <header class="page__header">
                <h2>{{title}}</h2>
                <div class="page__header__right">
                    <slot name="header"></slot>
                </div>
            </header>
            <hr>
            <main class="page__content">
                <slot></slot>
            </main>
        </section>
    </div>
</template>

<script>
    export default {
        name: "Page",
        props: {
            title: String
        }
    }
</script>

<style scoped>

</style>
