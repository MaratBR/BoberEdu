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
                    <router-link to="#" v-for="c in category.popular">
                        <div class="course-sm">
                            <img class="course-sm__img" src="https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__340.jpg">
                            <div class="course-sm__about">
                                <span class="course-sm__name">{{ c.name }}</span><br>
                                <span class="course-sm__cap">by TODO Team</span>
                                <span class="course-sm__rate">{{ c.rating }}</span>
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

                <loader :promise="coursesPromise" v-slot="{value}">

                    <div class="course-w" v-for="c in value.data">
                        <img class="course-w__pic" src="https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__340.jpg">

                        <div class="course-w__l">
                            <router-link :to="{}" class="course-w__name">{{ c.name }}</router-link><br>
                            <span class="course-w__cap">by TODO Team | {{ c.info.uc }} units | {{ c.info.lc }} lessons</span>
                        </div>

                        <div class="course-w__r">
                            <span class="course-w__rating">{{ c.rating }}</span><br>
                            <span class="course-w__price">$ {{ c.price }}</span>
                        </div>
                    </div>

                </loader>
            </section>
        </div>
    </div>
</template>

<script lang="ts">
    import {Component, Vue, Watch} from "vue-property-decorator";
    import Container from "../Container.vue";
    import Loader from "../misc/Loader.vue";
    import {dto} from "../../store/dto";
    import CategoryExDto = dto.CategoryExDto;
    import {Store} from "../../store";
    import {useStore} from "vuex-simple";
    import PaginationControl from "./PaginationControl.vue";

    @Component({
        components: {PaginationControl, Loader, Container}
    })
    export default class Category extends Vue {
        category: CategoryExDto = null;
        coursesPromise: Promise<dto.PaginationDto<dto.CoursePageItemDto>>;

        store: Store = useStore(this.$store);

        async loadData() {
            this.category = await this.store.courses.getCategory(+this.$route.params.id);
            this.coursesPromise = this.store.courses.getCoursesFromCategory(+this.$route.params.id)
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
        &__cat {
            padding: 15px
        }

        &__title {
            font-size: 2.3em;
            line-height: 80px
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
        margin: 4px;
        border: 1px solid #f0f0f0;
        border-radius: 15px;
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
        margin: 6px;
        border: 1px solid #f0f0f0;
        border-radius: 13px;
        overflow: hidden;
        display: flex;

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
