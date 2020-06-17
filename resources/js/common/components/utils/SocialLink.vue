<template>
    <a :href="href" class="social" :class="'social--' + type">
        <i class="fab" :class="'fa-' + icon"></i>
        <span>
            <slot></slot>
        </span>
    </a>
</template>

<script lang="ts">
    import {Vue, Component, Prop} from "@common";

    const ICONS_OVERRIDE = {
        linkedIn: 'linkedin'
    }

    @Component({
        name: "SocialLink"
    })
    export default class SocialLink extends Vue {
        @Prop() href: string;
        @Prop() type: string;

        get icon() {
            return ICONS_OVERRIDE[this.type] || this.type
        }
    }
</script>

<style scoped lang="scss">
    @use "sass/lib/functions";

    $links-colors: (
        "yt": (#ff0000, #fff),
        "fb": (#4267B2, #fff),
        'twitter': (#1DA1F2, #fff),
        'linkedIn': (#2867B2, #fff),
        'vk': (#4680C2, #fff)
    );

    .social {
        display: inline-flex;
        align-items: center;
        padding: 10px 15px;
        text-decoration: none;
        margin-bottom: 4px;

        & > .fab {
            margin-right: 10px;
        }

        @each $name, $color in $links-colors {
            &--#{$name} {
                background: nth($color, 1);
                color: nth($color, 2);

                &:hover {
                    background: darken(nth($color, 1), 10%);
                }
            }
        }
    }
</style>
