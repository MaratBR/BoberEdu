<template>
    <div class="cat-header">
        <div class="cat-header__bg"
             :style="{backgroundImage: imageId ? ('url(\'/storage/' + imageId + '\')') : null, backgroundColor: color}"></div>
        <div class="cat-header__mask" :style="{background: `radial-gradient(circle at center right, transparent, ${color} 65%)`}"></div>

        <div class="cat-header__about" :style="{borderColor: border, color: fg}">
            <h2>{{ name }}</h2>
            <p>{{ about }}</p>
            <hr>
            {{ students }} students | {{ courses }} courses
        </div>
    </div>
</template>

<script lang="ts">
    import {Vue, Component, Prop, Watch} from "@common";
    import {getBrightness, hexToRgb} from "@common/utils";

    @Component({
        name: "CategoryHeader"
    })
    export default class CategoryHeader extends Vue {
        @Prop() name: string;
        @Prop() about: string;
        @Prop() imageId: string;
        @Prop({ default: -1 }) courses: number;
        @Prop({ default: -1 }) students: number;
        @Prop({ default: '#000000' }) color: string;

        fg = null;
        border = null;

        @Watch('color')
        updatePalette() {
            let rgb = hexToRgb(this.color)
            let b = getBrightness(rgb)
            this.fg = b > 125 ? 'black' : 'white';
            this.border = b > 125 ? 'rgba(0, 0, 0, 0.2)' : 'rgba(255, 255, 255, 0.2)';
        }


        created() {
            this.updatePalette()
        }
    }
</script>

<style scoped lang="scss">
    .cat-header {
        position: relative;
        height: calc(100vh - 80px);
        display: flex;
        justify-content: flex-start;
        align-items: flex-end;

        &__bg, &__mask {
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            z-index: -1;
        }

        &__bg {
            background-size: cover;
        }

        &__about {
            margin: 0 0 50px 50px;
            border: 2px solid transparent;
            padding: 10px 25px 25px 25px;
            min-height: 150px;
            min-width: 300px;

            & > h2 {
                font-variant: all-small-caps;
            }

            & > p {
                opacity: 0.8;
            }
        }
    }
</style>
