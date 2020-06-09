<template>
    <sections>
        <admin-section header="Courses">
            <category-select v-model="category" default-text="All" />


            <courses-search :category-id="category ? category.id : null" />
        </admin-section>
    </sections>
</template>

<script lang="ts">
    import {Vue, Component, dto, Watch} from "@common";
    import Sections from "@admin/components/layout/Sections.vue";
    import AdminSection from "@admin/components/layout/AdminSection.vue";
    import CategorySelect from "@admin/components/courses/CategorySelect.vue";
    import PaginationControl from "@common/components/utils/PaginationControl.vue";
    import AdminStoreComponent from "@admin/components/AdminStoreComponent";
    import CoursesSearch from "@admin/components/courses/CoursesSearch.vue";

    @Component({
        name: "CoursesList",
        components: {CoursesSearch, PaginationControl, CategorySelect, AdminSection, Sections}
    })
    export default class CoursesList extends AdminStoreComponent {
        category: dto.CategoryDto = null
        pagination: dto.PaginationDto<dto.CoursePageItemDto> = null
        page = 0;

        async load() {
            this.pagination = null
            if (this.category)
                this.pagination = await this.store.courses.getCoursesFromCategory({
                    id: this.category.id,
                    page: this.page
                })
            else
                this.pagination = await this.store.courses.paginate(this.page)
        }

        @Watch('category')
        onCategory() {
            this.page = 1
            this.load()
        }

        signUpPeriod(c: dto.CoursePageItemDto) {
            if (c.requirements.signUp.beg === null)
                return '–'
            return new Date(c.requirements.signUp.beg).toDateString() + ' / ' + new Date(c.requirements.signUp.end).toDateString()
        }

        created() {
            this.load()
        }
    }
</script>

<style scoped lang="scss">

</style>
