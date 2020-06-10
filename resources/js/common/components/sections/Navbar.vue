<template>
    <section id="NavWrapper" v-if="active">
        <nav class="navbar navbar-expand-lg navbar-light bg-light" role="navigation">
            <router-link to="/" class="navbar-brand d-flex align-items-center">
                <img src="/assets/brand/cursed_bober.png" class="s-h60 mr-1" alt="">
                <h2 class="mb-0">Bober.Edu</h2>
            </router-link>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <nav-link-dropdown class="navbar-categories">
                        <template v-slot:label>
                            <i class="fas fa-th"></i>
                            <span class="d-md-none">Categories</span>
                        </template>

                        <template>
                            <router-link v-for="c in categories" :to="{name: 'category', params: {id: c.id}}"
                                         v-slot="{navigate, href, isActive}" :key="c.id">
                                <a class="dropdown-item" :href="href" @click="navigate" :class="{active: isActive}">
                                    {{ c.name }}
                                </a>
                            </router-link>
                            <hr>
                            <router-link class="dropdown-item" exact-active-class="active" :to="{name: 'categories'}">
                                <i class="fas fa-list"></i>
                                All categories
                            </router-link>
                        </template>
                    </nav-link-dropdown>
                </ul>

                <form class="mx-2 my-auto flex-grow-1">
                    <div class="input-group">
                        <input @keypress.enter.prevent="search($event.target.value)" type="text" class="form-control border border-right-0" placeholder="Search...">
                        <span class="input-group-append">
                            <button ref="searchButton" class="btn btn-outline-secondary border border-left-0" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                </form>

                <ul class="navbar-nav align-items-center flex-row m-2 m-md-0">
                    <li class="nav-item dropdown" v-if="store.isAuthenticated">
                        <a href="#" class="nav-link dropdown-toggle flex-row" role="button" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                            <img class="s60 img-thumbnail rounded-circle" :src="store.user.avatar">
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">
                            <router-link class="dropdown-item btn btn-light" :to="{name: 'profile', params: {id: store.user.id}}">Profile</router-link>
                            <router-link class="dropdown-item btn btn-light" :to="{name: 'profile_settings'}">Settings</router-link>
                            <router-link class="dropdown-item btn btn-light" :to="{name: 'profile_payments'}">Payments</router-link>
                            <template v-if="store.isAdmin">
                                <hr>
                                <router-link class="dropdown-item btn btn-light text-danger" :to="{name: 'admin'}">Admin</router-link>
                            </template>
                        </div>
                    </li>
                    <template v-else>
                        <li class="nav-item">
                            <router-link :to="{name: 'login'}" class="btn btn-success">Sign up</router-link>
                        </li>
                        <small class="text-muted m-1">or</small>
                        <li class="nav-item">
                            <router-link :to="{name: 'login'}" class="btn btn-outline-success">Log in</router-link>
                        </li>
                    </template>
                </ul>
            </div>
        </nav>
    </section>
</template>

<script lang="ts">

    import Component from "vue-class-component";
    import {StoreComponent} from "@common/components/utils";
    import NavLink from "@common/components/sections/NavLink.vue";
    import NavLinkDropdown from "@common/components/sections/NavLinkDropdown.vue";
    import {dto} from "@common";
    @Component({
        components: {NavLinkDropdown, NavLink}
    })
    export default class Navbar extends StoreComponent {
        categories: dto.CategoryExDto[] = [];
        readonly active = (new URLSearchParams(window.location.search)).get('navbar') !== 'hide'

        async search(request: string) {
            await this.$router.push({
                name: 'search',
                query: {
                    q: request
                }
            })
        }

        async created() {
            this.categories = (await this.store.getCategories()).categories
        }
    }
</script>

<style scoped lang="scss">

    .navbar-categories {
        border-radius: 5px;

        &:hover {
            background: rgba(black, 0.03);
        }
    }

    .navbar-brand:hover {
        outline: 1px dotted black;
    }
</style>
