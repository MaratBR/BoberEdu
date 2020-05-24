<template>
    <div class="category-view">
        <loader v-if="!category" />
        <header v-else class="category-view__head">
            <div class="category-view__cat container">
                <span class="category-view__title">{{ category.name }}</span><br>
                <span class="category-view__about">{{ category.about }}</span>
            </div>

            <div class="category-view__popular">
                <h3>Here is some popular courses for you</h3>
                <div class="category-view__popular__list">
                    <router-link :to="{name: 'course', params: {id: c.id}}" v-for="c in category.popular" :key="c.id">
                        <div class="course-sm">
                            <img class="course-sm__img" src="https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__340.jpg">
                            <div class="course-sm__about">
                                <span class="course-sm__name">{{ c.name }}</span><br>
                                <span class="course-sm__cap">by TODO Team</span>
                                <star-rating v-if="c.rating" :rating="c.rating" :fixed-points="1" :round-start-rating="false"
                                             :show-rating="false" :star-size="14" :read-only="false" />
                                <div v-else>No rating</div>
                                <span class="course-sm__price">$ {{ c.price }}</span>
                            </div>
                        </div>
                    </router-link>
                </div>
            </div>
        </header>

        <div class="container">

            <section class="courses-list">
                <div class="courses-list__opts">

                </div>

                <pagination-control @requestPage="page = $event && loadCourses()" :pagination="courses" v-slot="value">
                    <div class="course-w">
                        <img class="course-w__pic" src="https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__340.jpg">

                        <div class="course-w__l">
                            <router-link :to="{name: 'course', params: {id: value.id}}" class="course-w__name">{{ value.name }}</router-link><br>
                            <span class="course-w__cap">by TODO Team | {{ value.info.uc }} units | {{ value.info.lc }} lessons</span>
                        </div>

                        <div class="course-w__r">
                            <star-rating v-if="value.rating" :star-size="15" :read-only="true" :rating="value.rating"
                                         :round-start-rating="false" :fixed-points="1" />
                            <div v-else>No rating</div>
                            <span class="course-w__price"><span class="fa fa-usd"></span> {{ value.price }}</span>
                        </div>
                    </div>
                </pagination-control>
            </section>
        </div>
    </div>
</template>

<script lang="ts">
    import {Component, Watch} from "vue-property-decorator";
    import {Loader, PaginationControl, StoreComponent} from "@common/components/utils";
    import {dto, Prop} from "@common";

    @Component({
        components: {PaginationControl, Loader}
    })
    export default class Category extends StoreComponent {
        @Prop({required: true, type: Number}) categoryId: number;
        category: dto.CategoryExDto = null;
        courses: dto.PaginationDto<dto.CoursePageItemDto> = null;
        page = 1;

        async loadData() {
            this.category = await this.store.courses.getCategory(this.categoryId);
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