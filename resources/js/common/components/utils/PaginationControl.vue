<template>
    <section class="pagination">
        <ul class="pagination__pages" v-if="pagination">
            <li
                v-for="i in size"
                :key="i"
                class="pagination-item"
                :class="{active: i + leftCorner - 1 === pagination.meta.page}">
                <button class="btn" @click.prevent="page(i + leftCorner - 1)">{{i + leftCorner - 1}}</button>
            </li>
        </ul>

        <loader v-if="!pagination" />
        <slot v-else name="body" :items="pagination.data">
            <div class="pagination__items" v-if="pagination">
                <div class="page-item" v-for="(item, index) in pagination.data" :key="index">
                    <slot v-bind="item"></slot>
                </div>
            </div>
        </slot>


        <ul class="pagination__pages" v-if="pagination">
            <li
                v-for="i in size"
                :key="i"
                class="pagination-item"
                :class="{active: i + leftCorner - 1 === pagination.meta.page}">
                <button class="btn" @click.prevent="page(i + leftCorner - 1)">{{i + leftCorner - 1}}</button>
            </li>
        </ul>
    </section>
</template>

<script lang="ts">
    import {dto, Component, Prop, Vue} from "@common";
    import {Loader} from "@common/components/utils";

    @Component({
        components: {Loader}
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
