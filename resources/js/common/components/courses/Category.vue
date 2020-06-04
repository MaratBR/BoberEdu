<template>
    <div class="category-view">
        <category-header
            :name="name" :about="about" :color="'#' + color" :image-id="bgImage" :students="studentsCount"
            :courses="coursesCount" :id="categoryId" />

        <div class="container">

            <section class="courses-list">
                <div class="courses-list__opts">

                </div>

                <pagination-control @requestPage="page = $event && loadCourses()" :pagination="courses" v-slot="value">
                    <course-wide-card :course="value" />
                </pagination-control>
            </section>
        </div>
    </div>
</template>

<script lang="ts">
    import {Component, Watch} from "vue-property-decorator";
    import {Loader, PaginationControl, StoreComponent} from "@common/components/utils";
    import {dto, Prop} from "@common";
    import CategoryHeader from "@common/components/courses/CategoryHeader.vue";
    import CourseWideCard from "@common/components/courses/CourseWideCard.vue";

    @Component({
        components: {CourseWideCard, CategoryHeader, PaginationControl, Loader}
    })
    export default class Category extends StoreComponent {
        @Prop({required: true, type: Number}) categoryId: number;
        category: dto.CategoryExDto = null;

        name: string = null
        about: string = null
        studentsCount: number = null
        coursesCount: number = null
        color: string = null
        bgImage: string = null


        courses: dto.PaginationDto<dto.CoursePageItemDto> = null;
        page = 1;

        async loadData() {
            let category = await this.store.courses.getCategory(this.categoryId);
            this.name = category.name
            this.about = category.about
            this.bgImage = category.bgImage
            this.coursesCount = category.coursesCount
            this.studentsCount = category.studentsCount
            this.color = category.color
            await this.loadCourses()
        }

        async loadCourses() {
            this.courses = await this.store.courses.getCoursesFromCategory({
                id: this.categoryId,
                page: this.page
            })
        }

        created() {
            this.loadData()
        }

        @Watch('$route')
        onRouteChanged() {
            this.loadData();
        }
    }
</script>

<style scoped lang="scss">

    .category-view {
        &__head {
            background: #00a6f9 ;
            color: white;
            padding-bottom: 140px;
        }

        &__cat {
            padding: 15px
        }

        &__title {
            font-size: 2.3em;
            line-height: 90px;
            border-bottom: 3px solid white;
        }

        &__popular {
            padding: 10px;
            & > h3 {
                text-align: center;
            }

            &__list {
                display: flex;
                justify-content: center;
                overflow: auto;

                & > a {
                    display: inline-block;
                    color: inherit;
                    text-decoration: inherit;

                    &:hover > .course-sm {
                        background: #f3f3f3;
                    }

                    & > .course-sm {
                        transition: box-shadow 0.1s;
                    }

                    &:focus > .course-sm {
                        background: #f0f0f0;
                        box-shadow: 0 0 0 3px rgba(0,0,0,0.15);
                        border-color: darkgray  ;
                    }
                }
            }
        }
    }


    .course-sm {
        color: black;
        margin: 4px;
        overflow: hidden;
        min-width: 200px;
        max-width: 300px;
        background: white;

        &__name {
            font-size: 1.15em;
        }

        &__cap {
            font-size: 0.8em;
            color: gray;
        }

        &__about {
            padding: 10px
        }
    }


    .course-w {
        margin-bottom: 13px;
        border: 2px solid #f0f0f0;
        overflow: hidden;
        display: flex;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.04);

        &__name {
            display: inline-block;
            padding: 4px;
            border-radius: 2px;

            &:hover {
                background: rgba(0, 0, 0, 0.05);
            }
        }

        &__pic {
            max-width: 150px;
            max-height: 100px;
        }

        &__cap {
            font-size: 0.8em;
            color: gray;
        }

        &__l, &__r {
            padding: 10px;
        }

        &__l {
            margin-right: auto;
        }
    }



</style>
