<template>
    <div class="container">
        <loader v-if="!course && !err404" />

        <not-found v-else-if="err404">
            Course not found
        </not-found>

        <template v-else>
            <div class="row course-view">
                <div class="col-lg-4 d-flex justify-content-center p-4">
                    <img :src="course.image" class="img-thumbnail s270">
                </div>

                <div class="course-view__about col-lg-4">
                    <div class="course-about m-3">
                        <div class="course-about__cat">
                            <router-link :to="{name: 'category', params: {id: course.category.id}}"><i class="fas fa-chevron-left"></i> {{ course.category.name }}</router-link>
                        </div>
                        <span class="course-about__name">{{ course.name }}</span><br>
                        <span class="course-about__cap">by TODO Team | {{ unitsCount }} units | {{ lessonsCount }} lessons</span><br>
                        <star-rating :rating="course.rating || undefined" :star-size="hasAccess ? 45 : 25"
                                     :read-only="!this.hasAccess" :fixed-points="1" :max-rating="5"
                                     :round-start-rating="false" @rating-selected="rateCourse($event)" />
                        <span v-if="!course.rating">No one rated this course yet, you can be first</span>
                        <span v-else>{{ course.ratingVotes }} people voted</span>
                    </div>
                </div>

                <div class="course-view__buy col-lg-4 d-flex justify-content-center align-items-center">
                    <div class="course-buy">
                        <p v-if="!course.available">This course is not available for purchase</p>
                        <template v-else-if="!course.requirements.signUp.purchasable">
                            <p>This course can't be purchased right now</p>
                            <p>Course registration will be open on {{ new Date(course.requirements.signUp.beg).toLocaleDateString() }}</p>
                        </template>
                        <template v-else-if="hasAccess">
                            <p>
                                You purchased this course. You're golden.<br>
                                Here's your winner dance:
                            </p>
                            <div class="d-flex justify-content-center">
                                <img src="https://media.giphy.com/media/XbVH2Ei6TGr2rTNmYx/giphy.gif" class="s180" alt="">
                            </div>
                        </template>
                        <template v-else>
                            <div class="d-flex flex-column">
                                <p v-if="!enrolled">
                                    Purchase this course and have full access to everything it has to offer!
                                </p>
                                <p v-else-if="course.trialDays > 0">
                                    <template v-if="isTrialExpired">
                                        Your free trial period has expired, please purchase the course
                                    </template>
                                    <template v-else>
                                        You've joined free trial period
                                    </template>
                                </p>
                                <p v-else>
                                    You haven't completed the payment!
                                </p>



                                <template v-if="course.trialDays > 0 && !isTrialExpired">
                                    <button @click="enroll(true)" v-if="!enrolled"
                                            class="btn btn-outline-secondary" :disabled="joining">
                                        {{ joining ? '...' : 'Join free trial period' }}
                                    </button>
                                    <button @click="enroll(false)" v-if="enrolled"
                                            class="btn btn-outline-secondary btn-sm" :disabled="joining">
                                        {{ joining ? '...' : 'Leave trial period' }}
                                    </button>
                                    <small class="text-muted m-1 align-self-center">or</small>
                                </template>

                                <button @click="buy()" v-if="!hasAccess" class="btn btn-outline-success">
                                    {{course.trialDays === 0 && enrolled ? 'Complete the payment process' : 'Purchase the course'}}
                                </button>
                                <template v-if="isTrialExpired || course.trialDays === 0 && enrolled">
                                    <small class="text-muted m-1 align-self-center">or</small>
                                    <button @click="enroll(false)" class="btn btn-light text-muted btn-sm d-inline-block">Leave the course</button>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="course-body container">
                <markdown-viewer :value="course.about" />

                <section class="course-body__units">
                    <div class="units-list">
                        <div v-for="unit in course.units" :key="unit.id" class="unit-item" :class="{'active': isUnitOpen(unit.id)}">
                            <div class="unit-item__header" @click="toggleUnit(unit.id)">
                                <span class="unit-item__name">{{ unit.name }}</span>
                                <span class="unit-item__about">{{ unit.lessons.length }} lessons</span>
                                <span class="unit-item__preview" v-if="unit.preview">FREE PREVIEW</span>
                            </div>

                            <ul class="unit-item__lessons">
                                <li class="lesson-item" v-for="lesson in unit.lessons" :key="lesson.id">
                                    <router-link :to="{name: 'lesson', params: {v: course.id + '_' + lesson.id}}"
                                                 class="lesson-item__name">{{ lesson.title }}</router-link>
                                </li>
                            </ul>
                        </div>
                    </div>
                </section>

                <section class="course-body__teachers">
                    <h2>Teachers</h2>
                    <div class="d-flex">
                        <router-link v-for="t in course.teachers" :key="t.id" :to="{name: 'teacher', params: {id: t.id}}">
                            <div class="teacher mx-2">
                                <img class="img-thumbnail rounded-circle s120" :src="t.avatar"></img>
                                <span>{{ t.fullName }}</span>
                            </div>
                        </router-link>
                    </div>
                </section>
            </div>
        </template>
    </div>

</template>

<script lang="ts">
    import {Component, Watch} from "vue-property-decorator";
    import {dto} from "@common";
    import {Loader, StoreComponent, Error, MarkdownViewer} from "@common/components/utils";
    import NotFound from "@common/components/pages/NotFound.vue";

    @Component({
        components: {NotFound, MarkdownViewer, Error, Loader}
    })
    export default class CourseView extends StoreComponent {
        courseId: number | null = null;
        course: dto.CourseExDto | null = null;
        selectedUnits: number[] = [];
        hasAccess: boolean = false;
        enrolled: boolean = false;
        joining: boolean = false;
        err404: boolean = false;
        isTrialExpired: boolean = false;
        inProgress: boolean = true

        get unitsCount() { return this.course.units.length }

        get lessonsCount() {
            let v = 0;

            for (let unit of this.course.units) {
                v += unit.lessons.length
            }

            return v
        }

        created(): void {
            this.init()
        }

        async init() {
            this.inProgress = true
            this.courseId = +this.$route.params.id || null;
            try {
                this.course = await this.store.courses.get(this.courseId);
                if (this.store.isAuthenticated) {
                    await this.updateStatus();
                }
            } catch (e) {
                this.err404 = true;
            } finally {
                this.inProgress = false
            }
        }

        async updateStatus() {
            let status = await this.store.courses.status(this.courseId);
            this.hasAccess = status.hasAccess;
            this.enrolled = status.enrolled;
            this.isTrialExpired = status.trialEnd && +new Date(status.trialEnd) <= +new Date()
        }

        async enroll(enroll: boolean) {
            if (!this.store.isAuthenticated) {
                await this.$router.push({ name: 'login' });
                return;
            }

            this.joining = true;
            if (enroll)
                await this.store.courses.enroll(this.course.id);
            else
                await this.store.courses.disenroll(this.course.id);
            await this.updateStatus();
            this.joining = false;
        }

        async buy() {
            if (!this.store.isAuthenticated) {
                await this.$router.push({ name: 'login' });
                return;
            }

            await this.enroll(true);
            await this.$router.push({name: 'purchase_course', params: {id: this.course.id+''}});
        }

        async rateCourse(v) {
            await this.store.courses.setRate({
                courseId: this.course.id,
                value: v
            })
        }

        toggleUnit(id: number) {
            if (this.selectedUnits.indexOf(id) !== -1) {
                this.selectedUnits.splice(this.selectedUnits.indexOf(id))
            } else {
                this.selectedUnits.push(id)
            }
        }

        isUnitOpen(id: number): boolean {
            return this.selectedUnits.indexOf(id) !== -1
        }

        hasFreePreview(course): boolean {
            return course.units.some(u => u.preview)
        }

        @Watch('$route')
        routeChanged() {
            if (this.$route.name == 'course')
                this.init();
        }
    }
</script>

<style lang="scss" scoped>
    @import "../../../../sass/config";

    .course-view {

        &__about {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        &__actions {
            display: flex;
            flex-direction: column;
            align-items: stretch;

            & > button {
                margin: 5px;
                flex-grow: 1;
            }
        }

        &__buy {
            padding: 10px;
        }
    }

    .course-buy {
        background: #fafafa;
        border: 1px solid #f7f7f7;
        box-shadow: 0 4px 10px -2px #eee;
        padding: 10px;
    }

    .course-about {
        padding: 25px;

        &__name {
            font-size: 2em;
        }

        &__cap {
            font-size: 0.8em;
            color: gray;
        }
    }

    .course-body {
        &__teachers {
            a {
                text-decoration: none;
                color: black;

                &:hover .avatar {
                    box-shadow: 0 0 0 5px rgba(0, 0, 0, 0.3);
                }
            }
        }
    }

    .opinion {
        background: #fbf8ca;
        padding: 15px;
        margin: 10px;

        &__author {
            text-align: right;
        }
    }

    .unit-item {
        margin-bottom: 8px;

        &.active &__lessons {
            display: block;
        }

        &.active &__header {

        }

        &__header {
            font-size: 1.1em;
            padding: 17px;
            background: whitesmoke;
            cursor: pointer;
            display: flex;
            align-items: center;
            border-radius: 7px;

            &:hover {
                border-color: #999;
                background: #ddd;
            }
        }

        &__preview {
            display: inline-block;
            padding: 4px 4px 6px 4px;
            font-variant: all-small-caps;
            margin-left: auto;
            background: orange;
            color: white;
            font-weight: 700;
            border-radius: 4px;
        }

        &__lessons {
            display: none;

            list-style: none;
            padding-left: 10px;
        }

        &__about {
            margin-left: 10px;
            font-size: 0.9em;
            color: gray;
        }
    }

    .lesson-item {
        border-left: 2px solid #ddd;
        padding: 10px 15px;

        &:hover {
            border-color: #00bdbd;
            background: rgba(0,0,0,0.025);
        }

        &__name {

        }
    }

    .teacher {
        display: flex;
        flex-direction: column;
        align-items: center;

        & > .avatar {
            transition: .2s;
            margin-bottom: 7px;
        }
    }

</style>
