<template>
    <loader v-if="loading" />
    <not-found v-else-if="notFound" />
    <div class="lesson container" v-else>
        <div class="hero--phead lesson__hero">
            <ul class="breadcrumb">
                <li>
                    <router-link :to="{name: 'category', params: {id: course.category.id}}">{{ course.category.name }}</router-link>
                </li>
                <li>
                    <router-link :to="{name: 'course', params: {id: course.id}}">{{ course.name }}</router-link>
                </li>
                <li v-if="unitName">{{ unitName }}</li>
            </ul>
            <div class="lesson__summary">
                <span class="lesson__title">{{ lessonName }}</span>
                <p>TODO TODO TODO TODO</p>
            </div>

            <div class="lesson__loader" v-if="lessonLoading"></div>
        </div>

        <div class="lesson__body">
            <div v-if="showPurchase" class="notification purchase">
                <p v-if="showPurchase === 1">
                    It looks like your trial period has expired, please purchase course to have the access again
                </p>
                <p v-else>
                    We are terribly sorry but this lesson is not available when you purchased the course and not
                    included in free preview
                </p>

                <router-link
                    class="btn btn--primary"
                    :to="{name: 'course', params: {id: courseId}}">{{ showPurchase === 1 ? 'Purchase' : 'Join' }} course</router-link>
            </div>
            <markdown-viewer v-else :value="content" />
        </div>


        <aside class="lesson__menu">
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
    </div>

</template>

<script lang="ts">
    import {Component, Prop, Vue, Watch} from "vue-property-decorator";
    import {dto} from "../../store/dto";
    import {useStore} from "vuex-simple";
    import {Store} from "../../store";
    import Loader from "../misc/Loader.vue";
    import NotFound from "./NotFound.vue";
    import MarkdownViewer from "../misc/MarkdownViewer.vue";
    import LessonExDto = dto.LessonExDto;
    import CourseExDto = dto.CourseExDto;
    import EnrollmentStateDto = dto.EnrollmentStateDto;
    @Component({
        components: {MarkdownViewer, NotFound, Loader}
    })
    export default class Lesson extends Vue {
        @Prop({ required: true }) lessonId: number;
        @Prop({ required: true }) courseId: number;

        unitName: string = null;
        lessonName: string = null;
        content: string = null;

        course: CourseExDto = null;
        notFound = false;
        loading = true;
        isTrial = false;
        isTrialExpired = false;
        lessonLoading = false;
        enrolled = false;
        showPurchase = 0;

        store = useStore<Store>(this.$store)

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

                if ((this.isTrial && !unit.preview) || this.isTrialExpired) {
                    this.showPurchase = 1;
                } else if (!this.enrolled) {
                    this.showPurchase = 2;
                } else {
                    this.showPurchase = 0;
                    let lesson = await this.store.lessons.get(this.lessonId)
                    console.log(lesson)
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
    .lesson {
        padding-top: 20px;
        display: grid;
        grid-template-columns: 1fr 300px;
        grid-gap: 20px;

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
