<template>
    <page title="Courses List">
        <pagination :pagination="pagination" v-slot="c" @requestPage="load($event)">
            <div class="card course">
                <header class="course__name">
                    <h3>{{c.name}}</h3>
                </header>

                <div class="course__about">
                    <p><b>Units:</b> {{c.units_count}}</p>
                    <p><b>Lessons in total:</b> {{c.lessons_count}}</p>
                    <p><b>Price:</b> {{c.price}}</p>
                </div>

                <div class="course__buy">
                    <router-link class="btn" :to="{name: 'course', params: {id: c.id}}">More...</router-link>
                    <router-link class="btn" :to="{name: 'buy_course', params: {id: c.id}}">More...</router-link>

                </div>
            </div>

            <pre style="border: 1px solid red;">{{c}}</pre>
        </pagination>
    </page>
</template>

<script lang="ts">
    import Page from "./Page.vue";
    import Pagination from "./Pagination.vue";
    import {courses} from "../../api";
    export default {
        name: "CoursesList",
        components: {Pagination, Page},
        data() {
            return {
                pagination: null,
                currentPage: 1
            }
        },
        methods: {
            load(page = null) {
                if (page)
                    this.currentPage = page;
                this.pagination = null;

                this.$store.dispatch('courses/getCoursesPage', {page: this.currentPage})
                    .then(p => this.pagination = p)
            }
        },
        created(): void {
            this.load()
        }
    }
</script>

<style scoped lang="sass">
    .course
        display: flex
        justify-content: stretch

        &__name
            min-width: 300px

        &__about
            width: 100%

        &__buy
            display: flex
            flex-direction: column

</style>
