<template>
    <div id="AppContainer">

        <div id="ContentContainer" :class="{'has-overlay': showModal}">
            <navbar />
            <div id="ViewContainer">
                <keep-alive :max="4">
                    <router-view />
                </keep-alive>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
    import Navbar from "./components/Navbar.vue";
    import {Component, Vue} from "vue-property-decorator";

    @Component({
        components: {Navbar}
    })
    export default class App extends Vue {
        showModal: boolean = false ;
        modalComponent: string = null;
        modalProps: object = {};

    }
</script>

<style scoped lang="scss">
    .overlay {
        background: rgba(0, 0, 0, 0.8);
        position: fixed;
        height: 100%;
        width: 100%;
        top: 0;
        z-index: 10000;
        display: flex;
        justify-content: center;
        align-items: center;

        &__body {
            padding: 20px;
            background: white;
            border-radius: 10px;
        }
    }

    .has-overlay {
        filter: blur(5px) saturate(0.3);
    }

    .modal-enter-active, .modal-leave-active {
        transition: .3s;
        transform: scale(1);
    }
    .modal-enter, .modal-leave-to /* .fade-leave-active below version 2.1.8 */ {
        transform: scale(1.5);
        opacity: 0;
    }
</style>
