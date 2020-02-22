<template>
    <section id="NavWrapper">
        <nav :class="['nav', {'nav--active': mobileExpanded}]" role="navigation">
            <div class="nav__brand">
                <h2>EducationalBober</h2>
            </div>
            <section class="nav__links">
                <a href="" class="nav__links__a">1</a>
                <a href="" class="nav__links__a">2</a>
                <a href="" class="nav__links__a">3</a>
                <a href="" class="nav__links__a">4</a>
            </section>

            <section class="nav__right">
                <router-link :to="`/user/${$store.state.user.id}`" v-if="$store.getters.isAuthenticated ">{{$store.getters.username}}</router-link>
                <span v-if="$store.state.userSyncing">Loading...</span>
                <router-link v-if="!$store.getters.isAuthenticated && !$store.state.userSyncing" to="/login" class="btn btn--primary btn--inverted">Login</router-link>
                <router-link v-if="!$store.getters.isAuthenticated && !$store.state.userSyncing" to="/register" class="btn btn--primary btn--inverted">Sign Up</router-link>
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
    import {EventBus} from "../bus";
    import {api, User} from "../api";

    export default {
        name: "Navbar",
        data() {
            return {
                mobileExpanded: false
            }
        },
        methods: {
        },
        created(): void {

        },
        destroyed(): void {

        },
        mounted(): void {

        }
    }
</script>

<style scoped>
    nav {
        background: whitesmoke;
    }

    .nav__links__a {
        background: rgba(255,255,255,0.25);
        border-radius: 6px;
    }
</style>
