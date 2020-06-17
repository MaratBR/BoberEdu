<template>
    <loader v-if="loading" />
    <not-found v-else-if="notFound" />
    <div class="lesson container" v-else>
        <div class="lesson__hero">
            <ul class="breadcrumb breadcrumb-clear">
                <li class="breadcrumb-item">
                    <router-link :to="{name: 'category', params: {id: course.category.id}}">{{ course.category.name }}</router-link>
                </li>
                <li class="breadcrumb-item">
                    <router-link :to="{name: 'course', params: {id: course.id}}">{{ course.name }}</router-link>
                </li>
                <li class="breadcrumb-item" v-if="unitName">{{ unitName }}</li>
            </ul>
            <div class="lesson__summary">
                <span class="lesson__title">{{ lessonName }}</span>
                <p>{{ summary }}</p>
            </div>

            <div class="lesson__loader" v-if="lessonLoading"></div>
        </div>

        <div class="lesson__body">
            <div v-if="showPurchase" class="notification alert-warning alert">
                <p v-if="showPurchase === 1">
                    It looks like your trial period has expired, please purchase course to have the access again
                </p>
                <p v-else-if="showPurchase === 3">
                    We are sorry, but this lesson is not available in free trial period
                </p>
                <p v-else>
                    Please join free trial period or purchase the course to have the access to the lesson
                </p>

                <router-link class="btn btn-primary"
                    :to="{name: 'course', params: {id: courseId}}">{{ showPurchase !== 2 ? 'Purchase' : 'Join' }} course</router-link>
            </div>

            <aside class="lesson__menu float-right">
                <ul>
                    <li v-for="u in course.units">
                        <span>{{ u.name }}</span>

                        <ul>
                            <li v-for="l in u.lessons">
                                <router-link :to="{name: 'lesson', params: {v: courseId + '_' + l.id}}">{{ l.title }}</router-link>
                            </li>
                        </ul>
                    </li>
                </ul>
            </aside>

            <markdown-viewer :value="content" v-if="!showPurchase" />
            <div class="tease" v-else>
                <p v-for="i in 7" :key="i">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium aliquid autem beatae consectetur eius molestiae natus placeat porro possimus provident, quisquam ratione sint, tempora vel, velit? Enim in perspiciatis possimus!</p>
                <p>You didn't think it will actually work did you?</p>
            </div>

            <div class="clearfix"></div>

        </div>
    </div>

</template>

<script lang="ts">
    import {Component, Prop, Watch} from "vue-property-decorator";
    import {Loader, MarkdownViewer, StoreComponent} from "@common/components/utils";
    import {dto} from "@common";
    import NotFound from "@common/components/pages/NotFound.vue";

    @Component({
        components: {MarkdownViewer, NotFound, Loader}
    })
    export default class Lesson extends StoreComponent {
        @Prop({ required: true }) lessonId: number;
        @Prop({ required: true }) courseId: number;

        unitName: string = null;
        lessonName: string = null;
        content: string = '';
        summary: string = null
        course: dto.CourseExDto = null;
        notFound = false;
        loading = true;
        isTrial = false;
        isTrialExpired = false;
        lessonLoading = false;
        enrolled = false;
        showPurchase = 0;

        @Watch('courseId')
        async onCourseIdUpdated() {
            this.loading = true;
            try {
                this.course = await this.store.courses.get(this.courseId)
                let status = await this.store.courses.status(this.courseId)
                this.isTrial = !status.hasAccess && status.enrolled
                this.isTrialExpired = this.isTrial && (+new Date(status.trialEnd) < +new Date())
                this.enrolled = status.enrolled
            } catch (e) {
                this.notFound = true;
            }
            this.loading = false;
        }

        @Watch('lessonId')
        async onLessonIdUpdated() {
            this.lessonLoading = true;
            let unit = this.course.units.find(u => u.lessons.some(l => l.id == this.lessonId))

            if (unit) {
                this.unitName = unit.name;
                let lesson = unit.lessons.find(l => l.id == this.lessonId);
                this.lessonName = lesson.title

                if (this.isTrialExpired) {
                    this.showPurchase = 1;
                } else if (this.isTrial && !unit.preview) {
                    this.showPurchase = 3;
                } else if (!this.enrolled) {
                    this.showPurchase = 2;
                } else {
                    this.showPurchase = 0;
                    let lesson = await this.store.lessons.get(this.lessonId)
                    this.summary = lesson.summary
                    this.content = lesson.content;
                }
            } else {
                await this.$router.push({
                    name: 'lesson',
                    params: {
                        v: this.courseId + '_' + this.course.units[0].lessons[0].id
                    }
                })
            }
            this.lessonLoading = false;
        }

        created() {
            this.onCourseIdUpdated().then(this.onLessonIdUpdated)
        }
    }
</script>

<style scoped lang="scss">
    .tease {
        background: rgba(black, 0.01);
        user-select: none;
        pointer-events: none;
        padding: 10px;
        -webkit-mask-image: -webkit-gradient(linear, left top, left bottom,
            from(rgba(0,0,0,1)), to(rgba(0,0,0,0)));

        p {
            filter: blur(10px);
        }
    }

    .lesson {
        padding-top: 20px;

        &__title {
            font-size: 2.3em;
        }

        &__hero {
            grid-column: 1 / 3;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        &__loader {
            @keyframes loader-animation {
                0% {
                    left: 0;
                }
                100% {
                    left: calc(100% - 300px);
                }
            }

            height: 2px;
            position: absolute;
            width: 300px;
            bottom: 0;
            background: #03A9F4;
            animation-timing-function: linear;
            animation: loader-animation 1s infinite alternate;
        }

        &__menu {
            background: #f9f9f9;
            border-left: 1px solid #eee;
            padding: 20px 25px 30px 0;
            margin: 0 10px 10px 20px;
            min-width: 200px;

            & > ul {
                list-style: none;

                & > li {
                    font-size: 1.1em;

                    & > span {
                        font-weight: bold;
                        color: #00a6f9;
                        border-bottom: 2px solid;
                    }
                }
            }

            a {
                text-decoration: none;
                color: black;

                &.router-link-active {
                    font-weight: bold;
                }
            }
        }
    }
</style>
