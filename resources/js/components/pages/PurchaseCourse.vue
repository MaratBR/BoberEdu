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
                    <template v-if="!isAuthenticated">
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

                        <template v-else-if="value.can_join">
                            <p>You can join this course.</p>
                            <p v-if="value.has_preview">This course has free preview</p>
                            <button :disabled="submitting" class="btn btn--primary" @click="join()">
                                {{submitting ? 'Wait a moment...' : 'Join'}}
                            </button>
                        </template>

                        <template v-else>
                            <p>You can't join this course yet.</p>
                            <p v-if="value.will_be_available">Registration will be open in {{value.sign_up_beg}}</p>
                            <p v-else-if="value.was_available">Registration will be open in {{value.sign_up_end}}</p>
                        </template>

                    </loader>
                </div>
            </div>
        </loader>
    </page>
</template>

<script lang="ts">
    import Page from "./Page.vue";
    import {Course} from "../../models";
    import Loader from "../misc/Loader.vue";
    import Error from "../misc/Error.vue";
    import {PurchaseModel} from "../../store/modules/courses";
    import MarkdownViewer from "../misc/MarkdownViewer.vue";
    import {mapGetters} from "vuex";

    export default {
        name: "PurchaseCourse",
        components: {MarkdownViewer, Error, Loader, Page},
        data() {
            return {
                coursePromise: null,
                attendancePromise: null,
                submitting: false,
                currentPath: location.pathname
            }
        },
        computed: {
            ...mapGetters({
                isAuthenticated: 'auth/isAuthenticated'
            })
        },
        methods: {
            async purchase() {
                this.submitting = true;
                let r = await this.$store.dispatch('courses/purchase', this.$route.params.id);
                this.submitting = false;
                window.open(r.external_redirect_url, null, "width=500,height=500");
            },

            async join() {
                this.submitting = true;
                let r = await this.$store.dispatch('courses/join', this.$route.params.id);
                console.log(r)
                this.submitting = false;
                this.attendancePromise = this.$store.dispatch('courses/getAttendance', this.$route.params.id);
            },
            dateString: v => new Date(v).toLocaleDateString()
        },
        created(): void {
            this.coursePromise = this.$store.dispatch('courses/getCourse', this.$route.params.id);
            if (this.isAuthenticated)
                this.attendancePromise = this.$store.dispatch('courses/getAttendance', this.$route.params.id);
        },
        watch: {
            $route() {
                this.load()
            }
        }
    }
</script>

<style lang="sass" scoped>
    @import "resources/sass/lib/config"


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
