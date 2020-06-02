<template>
    <div class="search-bar control">
        <input @keypress="keyPress" type="text" @input="$emit('input', $event.target.value)" :value="value">
    </div>
</template>

<script lang="ts">
    import {Vue, Component, Prop} from "@common";

    @Component({
        name: "SearchBar"
    })
    export default class SearchBar extends Vue {
        lastTs = null;

        @Prop() value: string;
        @Prop({type: Boolean, default: false}) asYouType: string;
        @Prop({type: Number, default: 2000}) timeout: number;

        keyPress(e) {
            if (e.key === 'Enter') {
                this.$emit('search')
            }
        }
    }
</script>

<style scoped lang="scss">
    input {
        display: block;
        padding: 20px 10px;
        width: 100%;
        box-sizing: border-box;
        border-radius: 0;
        font-size: 17px;
        border: 2px solid #ccc;
        background: #fafafa;

        &:focus {
            outline: 2px dashed #333;
            border-color: #fff;
            box-shadow: none;

        }
    }
</style>
