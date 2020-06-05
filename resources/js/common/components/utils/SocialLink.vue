<template>
    <a :href="href" class="social" :class="'social--' + type">
        <i class="logo"></i>
        <span>
            <slot></slot>
        </span>
    </a>
</template>

<script lang="ts">
    import {Vue, Component, Prop} from "@common";

    @Component({
        name: "SocialLink"
    })
    export default class SocialLink extends Vue {
        @Prop() href: string;
        @Prop() type: string;
    }
</script>

<style scoped lang="scss">
    @use "sass/lib/functions";

    $links-colors: (
        "yt": #ff0000,
        "fb": #4267B2,
        'twitter': #1DA1F2,
        'linkedIn': #2867B2,
        'vk': #4680C2
    );

    .social {
        display: inline-flex;
        align-items: center;
        padding: 5px;

        & > .logo {
            height: 40px;
            width: 40px;
            display: block;
        }

        &--vk {
            background: #f2f2f2;

            & > .logo {
                background-image: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDIyLjEuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPgo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IgoJIHZpZXdCb3g9IjAgMCAyODMuNSAyODMuNSIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMjgzLjUgMjgzLjU7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPHN0eWxlIHR5cGU9InRleHQvY3NzIj4KCS5zdDB7ZmlsbC1ydWxlOmV2ZW5vZGQ7Y2xpcC1ydWxlOmV2ZW5vZGQ7ZmlsbDojNDY4MEMyO30KCS5zdDF7ZmlsbC1ydWxlOmV2ZW5vZGQ7Y2xpcC1ydWxlOmV2ZW5vZGQ7ZmlsbDojRkZGRkZGO30KPC9zdHlsZT4KPHBhdGggY2xhc3M9InN0MCIgZD0iTTEyMCw3MC45aDQzLjVjMzkuNywwLDQ5LjEsOS40LDQ5LjEsNDkuMXY0My41YzAsMzkuNy05LjQsNDkuMS00OS4xLDQ5LjFIMTIwYy0zOS43LDAtNDkuMS05LjQtNDkuMS00OS4xVjEyMAoJQzcwLjksODAuMyw4MC4zLDcwLjksMTIwLDcwLjkiLz4KPHBhdGggY2xhc3M9InN0MSIgZD0iTTE4Ni45LDExOS43YzAuNy0yLjIsMC0zLjgtMy4xLTMuOGgtMTAuM2MtMi42LDAtMy44LDEuNC00LjUsMi45YzAsMC01LjMsMTIuOC0xMi43LDIxLjEKCWMtMi40LDIuNC0zLjUsMy4yLTQuOCwzLjJjLTAuNywwLTEuNi0wLjgtMS42LTN2LTIwLjVjMC0yLjYtMC44LTMuOC0zLTMuOGgtMTYuMmMtMS42LDAtMi42LDEuMi0yLjYsMi40YzAsMi41LDMuNywzLjEsNC4xLDEwLjEKCXYxNS4yYzAsMy4zLTAuNiwzLjktMS45LDMuOWMtMy41LDAtMTItMTIuOS0xNy4xLTI3LjZjLTEtMi45LTItNC00LjYtNEg5OC4yYy0zLDAtMy41LDEuNC0zLjUsMi45YzAsMi43LDMuNSwxNi4zLDE2LjMsMzQuMwoJYzguNSwxMi4zLDIwLjYsMTguOSwzMS41LDE4LjljNi42LDAsNy40LTEuNSw3LjQtNHYtOS4zYzAtMywwLjYtMy41LDIuNy0zLjVjMS41LDAsNC4yLDAuOCwxMC4zLDYuN2M3LDcsOC4yLDEwLjIsMTIuMSwxMC4yaDEwLjMKCWMzLDAsNC40LTEuNSwzLjYtNC40Yy0wLjktMi45LTQuMy03LjEtOC43LTEyLjFjLTIuNC0yLjgtNi01LjktNy4xLTcuNGMtMS41LTItMS4xLTIuOCwwLTQuNkMxNzMsMTQzLjUsMTg1LjYsMTI1LjcsMTg2LjksMTE5LjciLz4KPC9zdmc+Cg==");
            }
        }
    }
</style>
