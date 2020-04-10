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
    import Pagination from "./PaginationControl.vue";
    import {CoursesPagination} from "../../store/modules/CoursesModule"
    import {Component, Vue, Watch} from "vue-property-decorator";
    import {Store} from "../../store";
    import {useStore} from "vuex-simple";

    @Component({
        components: {Pagination, Page}
    })
    export default class CoursesList extends Vue {
        pagination: CoursesPagination = null;
        currentPage: number;
        store: Store = useStore(this.$store);

        async load(page = null) {
            if (page == null)
            {
                if (this.pagination)
                    return;
                page = this.currentPage;
            }
            else if (page == this.currentPage)
                return;
            this.currentPage = page;
            await this.loadData();
            await this.$router.push({ path: this.$route.path, query: { p: page } })
        }

        async loadData() {
            this.pagination = await this.store.courses.paginate(this.currentPage)
        }

        created(): void {
            this.currentPage = +this.$route.query.p || 1;
            this.loadData()
        }

        @Watch('$route')
        routeChanged() {
            let newPage = +this.$route.query.p;
            if (!isNaN(newPage) && newPage > 0)
            {
                this.currentPage = newPage;
                this.loadData();
            }
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
