<template>
    <router-link :to="to || '#'" v-slot="{ href, isActive, navigate, isExactActive }">
        <li class="nav-item dropdown" :class="{active: isActive, 'exact-active': isExactActive}">
            <a class="nav-link dropdown-toggle" :href="href" @click.prevent="navigate" data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false" :id="id">
                <slot name="label">
                    {{ label }}
                </slot>
            </a>
            <div class="dropdown-menu" :aria-labelledby="id">
                <slot></slot>
            </div>
        </li>
    </router-link>
</template>

<script lang="ts">
    import {Vue, Component, Prop} from "@common";

    @Component({
        name: "NavLinkDropdown"
    })
    export default class NavLinkDropdown extends Vue {
        readonly id = 'Link' + Math.round(Math.random()*1000)

        @Prop() to: any;
        @Prop() label: any;
    }
</script>

<style scoped lang="scss">

</style>
