<template>
    <page title="Purchasing a course">
        <loader :promise="coursePromise" v-slot="{value}">
            <div class="purchase">
                <div class="purchase__body">
                    <div class="purchase__name">
                        <h3>{{value.name}}</h3>
                    </div>
                    <div class="purchase__about">
                        <markdown-viewer :value="value.about" />
                    </div>

                    <div class="purchase__links">
                        <router-link class="btn" :to="{name: 'course', params: {id: value.id}}">
                            <i class="fa fa-arrow-left"></i>
                            Back to course
                        </router-link>
                    </div>
                </div>

                <div class="purchase__aside">
                    <template v-if="!store.auth.isAuthenticated">
                        <p>Please, log in first to purchase this course</p>
                        <router-link class="btn btn--primary" :to="{name: 'login', query: {next: currentPath}}">Login</router-link>
                    </template>
                    <loader v-else :promise="attendancePromise" v-slot="attendance">
                        <template v-if="attendance.value">
                            <div class="purchase__price">
                                $ {{value.price}}
                            </div>

                            <div class="purchase__actions">
                                <template v-if="attendance.value.active">
                                    <router-link class="btn btn--primary" :to="{name: 'course', params: {id: value.id}}">Go to course</router-link>
                                </template>
                                <template v-else>
                                    <p class="p p--primary">You've joined this course at <b>{{dateString(attendance.value.created_at)}}</b></p>
                                    <p>You can purchase this course</p>
                                    <button class="btn btn--primary" @click="purchase()">
                                        {{submitting ? 'Purchasing...' : 'Purchase'}}
                                    </button>

                                </template>
                            </div>
                        </template>

                        <template v-else-if="canJoin(value)">
                            <p>You can join this course.</p>
                            <p :test="hasPreview(value)">This course has free preview</p>
                            <button :disabled="submitting" class="btn btn--primary" @click="join()">
                                {{submitting ? 'Wait a moment...' : 'Join'}}
                            </button>
                        </template>

                        <template v-else>
                            <p>You can't join this course yet.</p>
                            <p v-if="willBeAvailable(value)">Registration will be open in {{value.sign_up_beg}}</p>
                            <p v-else-if="wasAvailable(value)">Registration will be open in {{value.sign_up_end}}</p>
                        </template>

                    </loader>
                </div>
            </div>
        </loader>
    </page>
</template>

<script lang="ts">
    import Page from "./Page.vue";
    import Loader from "../misc/Loader.vue";
    import Error from "../misc/Error.vue";
    import MarkdownViewer from "../misc/MarkdownViewer.vue";
    import {mapGetters} from "vuex";
    import {Component, Vue, Watch} from "vue-property-decorator";
    import {Store} from "../../store";
    import {useStore} from "vuex-simple";
    import {Course, CourseEx} from "../../store/modules/CoursesModule";

    @Component({
        components: {MarkdownViewer, Error, Loader, Page}
    })
    export default class PurchaseCourse extends Vue {
        coursePromise = null;
        attendancePromise = null;
        submitting = false;
        currentPath = location.pathname

        store: Store = useStore(this.$store);

        async purchase() {
            this.submitting = true;
            let r = await this.$store.dispatch('courses/purchase', this.$route.params.id);
            this.submitting = false;
            window.open(r.external_redirect_url, null, "width=500,height=500");
        }

        async join() {
            this.submitting = true;
            let r = await this.$store.dispatch('courses/join', this.$route.params.id);
            console.log(r)
            this.submitting = false;
            this.attendancePromise = this.store.courses.getAttendance(+this.$route.params.id).catch(() => null);
        }

        dateString(v) { return new Date(v).toLocaleDateString() }

        canJoin(course: Course) {
            return course.available && !this.willBeAvailable(course) && !this.wasAvailable(course)
        }

        willBeAvailable(course: Course) {
            let now = +new Date();
            return course.sign_up_beg && now < +new Date(course.sign_up_beg)
        }

        wasAvailable(course: Course) {
            let now = +new Date();
            return course.sign_up_end && now > +new Date(course.sign_up_end)
        }

        hasPreview(course: CourseEx) {

        }

        load() {
            this.coursePromise = this.store.courses.get(+this.$route.params.id);
            if (this.store.auth.isAuthenticated)
                this.attendancePromise = this.store.courses.getAttendance(+this.$route.params.id).catch(() => null);
        }

        created(): void {
            this.load()
        }

        @Watch('$route')
        routeChanged() {
            this.load()
        }
    }
</script>

<style lang="sass" scoped>
    @import "../../../sass/lib/config"


    .purchase
        display: grid
        grid-template-columns: auto 300px

        &__about
            background: #fafafa
            padding: 10px
            border: 1px solid #eee
            margin: 0 15px 0 0

        &__aside
            padding: 30px 10px 30px 30px
            border-left: 1px solid #eee

            & .btn
                width: 100%

        &__price
            font-size: 2em
            padding: 15px 5px
            background: rgba(0, 0, 0, 0.03)
            text-align: center

        &__links
            margin-top: 20px


        @media ($ss-breakpoint-tablet)
            grid-template-columns: 1fr
            grid-template-rows: auto auto

            &__aside
                border: 1px solid #eee
                background: #fafafa
                grid-row: 1

            &__body
                grid-row: 2

</style>
