<template>
    <section class="pagination">
        <div class="pagination__items" v-if="pagination">
            <div class="page-item" v-for="item in pagination.data">
                <slot v-bind="item">
                    <pre>{{item}}</pre>
                </slot>
            </div>
        </div>


    </section>
</template>

<script lang="ts">
    import Pagination from "../../models/pagination";
    import {PropOptions} from "vue/types/options";

    export default {
        name: "Pagination",
        props: {
            pagination: {
                type: Object
            } as PropOptions<Pagination<any>>
        }
    }
</script>

<style scoped>

</style>
