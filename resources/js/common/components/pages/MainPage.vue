<template>
    <div class="hero">
        <div class="rounded-curve rounded-curve--animated black-glass h-100 d-flex align-items-center">
            <img src="/assets/brand/beaver_main.jpg" class="" alt="">
        </div>

        <div class="text-white pl-4">
            <div class="display-3">
                "Learning is light and ignorance is darkness"
            </div>

            <div class="join" v-if="!store.isAuthenticated">
                <router-link class="btn btn-outline-secondary btn-lg text-white" :to="{name: 'register'}">Join now!</router-link>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
    import {Vue, Component} from "@common";
    import {StoreComponent} from "@common/components/utils";

    @Component({
        name: "MainPage"
    })
    export default class MainPage extends StoreComponent {

    }
</script>

<style scoped lang="scss">
    .join {
        padding: 3rem;
    }

    .black-glass {
        background: rgba(black, 0.3);
    }

    .hero {
        background: #1e4363;
        display: flex;
        align-items: center;
        height: 100vh;

        img {
            height: 40rem;
        }

        *::selection {
            background: white;
        }
    }

    .rounded-curve {
        clip-path: ellipse(100% 130% at 0% 50%);

        &--animated {
            clip-path: ellipse(100% 0% at 0% 50%);
            @keyframes animation {
                0% {
                    clip-path: ellipse(100% 0% at 0% 50%);
                }

                100% {
                    clip-path: ellipse(100% 130% at 0% 50%);
                }
            }

            animation: animation 0.4s 0.3s alternate;
            animation-fill-mode: forwards;
        }
    }
</style>
