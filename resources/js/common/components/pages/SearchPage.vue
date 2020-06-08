<template>
    <div class="container">
        <form action="#" class="pt-4 p-2 search-form">
            <div class="input-group">
                <input ref="input" @keypress.enter.prevent="search($event.target.value)" type="text"
                       class="form-control form-control-plaintext search-control border-bottom" placeholder="Search...">
                <button ref="searchButton" class="btn ml-2" type="button">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </form>

        <div class="search-results">
            <pagination-control :pagination="pagination" v-slot="item">
                <course-wide-card :course="item" />
            </pagination-control>
        </div>
    </div>
</template>

<script lang="ts">
    import {Vue, Component, Prop, Watch, dto} from "@common";
    import Loader from "@common/components/utils/Loader.vue";
    import {StoreComponent} from "@common/components/utils";
    import PaginationControl from "@common/components/utils/PaginationControl.vue";
    import CourseWideCard from "@common/components/courses/CourseWideCard.vue";

    @Component({
        name: "SearchPage",
        components: {CourseWideCard, PaginationControl, Loader}
    })
    export default class SearchPage extends StoreComponent {
        @Prop({ type: String }) q: string;
        pagination: dto.PaginationDto<dto.CoursePageItemDto> = null


        @Watch('q')
        async performSearch() {
            this.pagination = null;
            ;(this.$refs.input as HTMLInputElement).value = this.q

            this.pagination = await this.store.searchCourses(this.q)
        }

        async search(q: string) {
            if (q == this.q)
                return
            await this.$router.push({
                name: 'search',
                query: {
                    q
                }
            })
        }

        mounted() {
            this.performSearch()
        }
    }
</script>

<style scoped lang="scss">
    .search-control {
        padding-left: 1rem;
        font-size: 1.2em;
    }

    .search-results {
        padding-top: 1rem;
    }
</style>
