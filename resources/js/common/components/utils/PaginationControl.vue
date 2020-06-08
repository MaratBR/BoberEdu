<template>
    <section class="items">
        <ul class="pagination" v-if="pagination && pagination.data.length > 0">
            <li
                v-for="i in size"
                :key="i"
                class="page-item"
                :class="{active: i + leftCorner - 1 === pagination.meta.page}">
                <button class="page-link" @click.prevent="page(i + leftCorner - 1)">{{i + leftCorner - 1}}</button>
            </li>
        </ul>

        <div v-if="!pagination">
            <slot name="pulse">
                <div class="d-flex">
                    <div class="pulse inline-block s1" v-for="_ in 5"></div>
                </div>
                <div class="pulse s2" v-for="_ in 5"></div>
                <br>
                <div class="d-flex justify-center">
                    <div class="pulse s2 pulse--dot"></div>
                    <div class="pulse s2 pulse--dot"></div>
                    <div class="pulse s2 pulse--dot"></div>
                </div>
            </slot>
        </div>
        <slot v-else-if="pagination.data.length !== 0" name="body" :items="pagination.data">
            <div class="items__list" v-if="pagination">
                <div class="page-item" v-for="(item, index) in pagination.data" :key="index">
                    <slot v-bind="item"></slot>
                </div>
            </div>
        </slot>
        <slot v-else name="not-found">
            <not-found>
                Nothing found
            </not-found>
        </slot>


        <ul class="pagination" v-if="pagination && pagination.data.length > 0">
            <li
                v-for="i in size"
                :key="i"
                class="page-item"
                :class="{active: i + leftCorner - 1 === pagination.meta.page}">
                <button class="page-link" @click.prevent="page(i + leftCorner - 1)">{{i + leftCorner - 1}}</button>
            </li>
        </ul>
    </section>
</template>

<script lang="ts">
    import {dto, Component, Prop, Vue} from "@common";
    import {Loader} from "@common/components/utils";
    import NotFound from "@common/components/pages/NotFound.vue";

    @Component({
        components: {NotFound, Loader}
    })
    export default class PaginationControl extends Vue {
        @Prop() pagination: dto.PaginationDto<any>;

        page(num: number) {
            this.$emit('requestPage', num);
        }

        get leftCorner() {
            return Math.max(1, this.pagination.meta.page - 3)
        }
        get size() {
            return Math.min(7, this.pagination.meta.lastPage - this.leftCorner + 1)
        }
    }
</script>

<style scoped lang="sass">
    .pagination
        &__pages
            list-style: none

    .pagination-item
        display: inline-block
        margin: 5px

        &.active > button
            background: lightblue
</style>
