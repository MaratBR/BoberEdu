<template>
    <div class="not-found">
        <div class="not-found__image" :style="{backgroundImage: 'url(\'' +image+ '\')'}" />

        <div class="not-found__body">
            <h2>This is beaver. {{ text }}. What else did you expect?</h2>
            <slot>
                <p>Page you were looking for is not found</p>
                <p>Please check the URL and make sure that it's ok</p>
            </slot>
        </div>
    </div>
</template>

<script lang="ts">
    import {Vue, Component} from "@common";

    let images: [string, string][] = [
        ['https://media.giphy.com/media/5UnDMSr73JwAw/giphy.gif', 'He is eating']
    ]

    @Component({
        name: "NotFound"
    })
    export default class NotFound extends Vue {
        image = null;
        text = null;

        created() {
            let index = Math.ceil(Math.random()*images.length)-1
            let v = images[index]
            this.image = v[0]
            this.text = v[1]
        }
    }
</script>

<style scoped lang="scss">
    .not-found {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 25px;

        &__image {
            margin: 15px;
            height: 250px;
            min-width: 250px;
            border-radius: 1000px;
            background-size: cover;
            background-position: center;
        }
    }
</style>
