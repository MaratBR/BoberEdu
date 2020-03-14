<template>
    <section class="pagination">
        <ul class="pagination__pages" v-if="pagination">
            <li
                v-for="i in size"
                class="pagination-item"
                :class="{active: i + leftCorner - 1 === pagination.current_page}">
                <button class="btn" @click.prevent="page(i + leftCorner - 1)">{{i + leftCorner - 1}}</button>
            </li>
        </ul>

        <loader :loading="!pagination" :promise-mode="false">
            <div class="pagination__items" v-if="pagination">
                <div class="page-item" v-for="item in pagination.data">
                    <slot v-bind="item">
                        <pre>{{item}}</pre>
                    </slot>
                </div>
            </div>
        </loader>

        <ul class="pagination__pages" v-if="pagination">
            <li
                v-for="i in size"
                class="pagination-item"
                :class="{active: i + leftCorner - 1 === pagination.current_page}">
                <button class="btn" @click.prevent="page(i + leftCorner - 1)">{{i + leftCorner - 1}}</button>
            </li>
        </ul>
    </section>
</template>

<script lang="ts">
    import Pagination from "../../models/pagination";
    import {PropOptions} from "vue/types/options";
    import Loader from "../misc/Loader.vue";

    export default {
        name: "Pagination",
        components: {Loader},
        props: {
            pagination: {
                type: Object
            } as PropOptions<Pagination<any>>
        },
        methods: {
            page(num: number) {
                this.$emit('requestPage', num);
            }
        },
        computed: {
            leftCorner() {
                return Math.max(1, this.pagination.current_page - 3)
            },
            size() {
                return Math.min(7, this.pagination.last_page - this.leftCorner + 1)
            }
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
