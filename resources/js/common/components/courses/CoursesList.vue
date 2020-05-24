<template>
    <page title="Courses List">
        <pagination-control :pagination="pagination" v-slot="c" @requestPage="load($event)">
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
        </pagination-control>
    </page>
</template>

<script lang="ts">
    import {Component, dto, Watch} from "@common";
    import {PaginationControl, StoreComponent} from "@common/components/utils";
    import Page from "@common/components/pages/Page.vue";

    // TODO make use of router's 'props' method

    @Component({
        components: {Page, PaginationControl}
    })
    export default class CoursesList extends StoreComponent {
        pagination: dto.PaginationDto<dto.CoursePageItemDto> = null;
        currentPage: number = 1;

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
