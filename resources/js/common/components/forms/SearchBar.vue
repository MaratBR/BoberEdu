<template>
    <div class="search-bar form-group">
        <input class="form-control-lg form-control" @keypress="keyPress" type="text" @input="$emit('input', $event.target.value)" :value="value">
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
</style>
