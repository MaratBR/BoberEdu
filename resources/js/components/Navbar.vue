<template>
    <section id="NavWrapper" v-if="active">
        <nav :class="['nav', {'nav--active': mobileExpanded}]" role="navigation">
            <div class="nav__brand">
                <h2>EducationalBober</h2>
            </div>
            <section class="nav__links">
                <router-link :to="{name: 'courses'}" class="nav__links__a">Courses</router-link>
                <router-link :to="{name: 'courses'}" class="nav__links__a">Courses</router-link>
                <router-link :to="{name: 'courses'}" class="nav__links__a">Courses</router-link>
                <router-link :to="{name: 'courses'}" class="nav__links__a">Courses</router-link>
                <router-link :to="{name: 'courses'}" class="nav__links__a">Courses</router-link>
            </section>

            <section class="nav__right">
                <router-link :to="`/user/${$store.state.auth.user.id}`"
                             v-if="$store.getters['auth/isAuthenticated'] ">{{$store.state.auth.user.name}}</router-link>
                <span v-if="$store.state.auth.syncing">Loading...</span>
                <router-link
                    v-if="!$store.getters['auth/isAuthenticated'] && !$store.state.auth.syncing"
                    to="/login" class="btn btn--primary btn--inverted">Login</router-link>
                <router-link
                    v-if="!$store.getters['auth/isAuthenticated'] && !$store.state.auth.syncing"
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
    export default {
        name: "Navbar",
        data() {
            return {
                mobileExpanded: false,
                active: (new URLSearchParams(window.location.search)).get('navbar') !== 'hide'
            }
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
