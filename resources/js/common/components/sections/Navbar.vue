<template>
    <section id="NavWrapper" v-if="active">
        <nav class="navbar navbar-expand-lg navbar-light bg-light" role="navigation">
            <div class="navbar-brand">
                <h2>Bober.Edu</h2>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Dropdown
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                    </li>

                    <li class="nav-item dropdown" v-if="store.isAuthenticated">
                        <a href="#" class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Hello
                        </a>

                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">Action</a>

                        </div>
                    </li>
                </ul>

                <div class="nav-item dropdown" v-if="store.isAuthenticated">
                    <a href="#" class="nav-link dropdown-toggle flex-row" role="button" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        <img class="s60 img-thumbnail rounded-circle" :src="store.user.avatar">
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <router-link class="dropdown-item btn btn-light" :to="{name: 'profile', params: {id: store.user.id}}">Profile</router-link>
                        <hr>
                        <router-link class="dropdown-item btn btn-light text-danger" :to="{name: 'admin'}" v-if="store.isAdmin">Admin</router-link>
                    </div>
                </div>
                <div class="flex-row" v-else>
                    <router-link :to="{name: 'login'}" class="btn btn-success" type="submit">Sign up</router-link>
                    <small class="text-muted">or</small>
                    <router-link :to="{name: 'login'}" class="btn btn-outline-success" type="submit">Log in</router-link>
                </div>
            </div>
        </nav>
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
