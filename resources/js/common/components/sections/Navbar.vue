<template>
    <section id="NavWrapper" v-if="active">
        <nav :class="['nav', {'nav--active': mobileExpanded}]" role="navigation">
            <div class="nav__brand">
                <h2>Bober.Edu</h2>
            </div>
            <section class="nav__links">
                <router-link :to="{name: 'courses'}" class="nav__links__a">Courses</router-link>
                <router-link :to="{name: 'courses'}" class="nav__links__a">Courses</router-link>
                <router-link :to="{name: 'courses'}" class="nav__links__a">Courses</router-link>
                <router-link :to="{name: 'courses'}" class="nav__links__a">Courses</router-link>
                <router-link :to="{name: 'courses'}" class="nav__links__a">Courses</router-link>
            </section>

            <section class="nav__right">
                <router-link :to="{name: 'profile', params: {id: store.auth.user.id}}" class="btn"
                             v-if="store.auth.isAuthenticated">{{store.auth.user.name}}</router-link>
                <span v-if="store.auth.loggingIn">Loading...</span>
                <router-link
                    v-if="!store.auth.isAuthenticated && !store.auth.loggingIn"
                    to="/login" class="btn btn--primary btn--inverted">Login</router-link>
                <router-link
                    v-if="!store.auth.isAuthenticated && !store.auth.loggingIn"
                        to="/register" class="btn btn--primary btn--inverted">Sign Up</router-link>
            </section>

            <div :class="['nav__burger', {'nav__burger--active': mobileExpanded}]" @click="mobileExpanded = !mobileExpanded">
                <i></i>
                <i></i>
                <i></i>
            </div>
        </nav>
        <div class="nav-placeholder"></div>
    </section>
</template>

<script lang="ts">

    import Component from "vue-class-component";
    import {StoreComponent} from "@common/components/utils";

    @Component
    export default class Navbar extends StoreComponent {
        mobileExpanded = false;
        readonly active = (new URLSearchParams(window.location.search)).get('navbar') !== 'hide'
    }
</script>

<style scoped>

    .nav__links__a {
        background: rgba(255,255,255,0.25);
        border-radius: 6px;
    }
</style>