<template>
    <router-link :to="to" v-slot="{ href, isActive, navigate, isExactActive }">
        <li class="nav-item" :class="{active: isActive, 'exact-active': isExactActive}">
            <a class="nav-link" :href="href" @click="navigate">
                <slot></slot>
            </a>
        </li>
    </router-link>
</template>

<script lang="ts">
    import {Vue, Component, Prop} from "@common";

    @Component({
        name: "NavLink"
    })
    export default class NavLink extends Vue {
        @Prop({ required: true }) to: any;
    }
</script>

<style scoped lang="scss">

</style>
