<template>
    <div class="course-view">
        <loader v-if="!course && !err404" />

        <p v-else-if="err404">
            Course not found
        </p>

        <template v-else>
            <section class="course-view__hero-wrp">
                <div class="course-view__hero container hero--phead">


                    <div class="course-view__pic">
                        <img src="https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__340.jpg">
                    </div>

                    <div class="course-view__about">
                        <div class="course-about">
                            <div class="course-about__cat">
                                <router-link :to="{name: 'category', params: {id: course.category.id}}"><i class="fa fa-chevron-left"></i> {{ course.category.name }}</router-link>
                            </div>
                            <span class="course-about__name">{{ course.name }}</span><br>
                            <span class="course-about__cap">by TODO Team | {{ unitsCount }} units | {{ lessonsCount }} lessons</span><br>
                            <star-rating :rating="course.rating || undefined" :star-size="hasAccess ? 45 : 25"
                                         :read-only="!this.hasAccess" :fixed-points="1" :max-rating="5"
                                         :round-start-rating="false" @rating-selected="rateCourse($event)" />
                            <span v-if="!course.rating">No one rated this course yet, you can be first</span>
                            <span v-else>{{ course.ratingVotes }} people voted</span>
                        </div>

                        <div class="course-view__actions">
                            <button @click="enroll(true)" v-if="!enrolled" class="btn btn--primary" :disabled="joining">
                                {{ joining ? '...' : 'Join' }}
                            </button>
                            <button @click="enroll(false)" v-if="enrolled" class="btn btn--primary" :disabled="joining">
                                {{ joining ? '...' : 'Leave' }}
                            </button>

                            <button @click="buy()" v-if="!hasAccess" class="btn btn--primary">Buy</button>
                        </div>
                    </div>
                </div>
            </section>

            <div class="course-body container">
                <div class="course-body__about">
                    <div class="notification notification--danger" v-if="isTrialExpired && !hasAccess">
                        Please note that your trial period is over, please purchase to have access to course again.
                    </div>
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
                        <div class="d--flex">
                            <router-link v-for="t in course.teachers" :key="t.id" :to="{name: 'teacher', params: {id: t.id}}">
                                <div class="teacher">
                                    <div class="avatar s90">
                                        <img src="https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__340.jpg"></img>
                                    </div>
                                    <span>{{ t.fullName }}</span>
                                </div>
                            </router-link>
                        </div>
                    </section>
                </div>
                <div class="course-body__opinions">
                    <div class="opinion" v-for="i in 2" :key="i">
                        <cite class="opinion__text">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium animi dignissimos doloremque eveniet exercitationem iure iusto tenetur voluptas? Facere in labore natus, neque omnis optio possimus repellat reprehenderit tempora ullam.
                        </cite>
                        <div class="opinion__author">-- Lorem</div>
                    </div>
                </div>
            </div>
        </template>
    </div>

</template>

<script lang="ts">
    import {Component, Watch} from "vue-property-decorator";
    import {dto} from "@common";
    import {Loader, StoreComponent, Error, MarkdownViewer} from "@common/components/utils";

    @Component({
        components: {MarkdownViewer, Error, Loader}
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
            this.courseId = +this.$route.params.id || null;
            try {
                this.course = await this.store.courses.get(this.courseId);
                if (this.store.isAuthenticated) {
                    await this.updateStatus();
                }
            } catch (e) {
                this.err404 = true;
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
        &__hero {
            @media ($ss-breakpoint-mobile) {
                display: block;
            }
            display: grid;
            grid-template-columns: 1fr 1fr;

            &-wrp {
                padding-bottom: 20px    ;
            }
        }

        &__about {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        &__l {
            display: flex;
            align-items: flex-end;
        }

        &__r {

        }

        &__actions {
            display: flex;
            justify-content: stretch;

            & > button {
                margin: 5px;
                flex-grow: 1;
            }
        }
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
        display: grid;
        grid-template-columns: 3fr 1fr;

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
