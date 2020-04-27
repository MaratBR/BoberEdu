<template>
    <loader v-if="!status" />
    <page v-else title="Purchasing course">
        <div class="notification" v-if="status.hasAccess">
            It seems like you already purchased this course.
        </div>

        <div class="payment" v-else>
            <tabs>
                <tab name="DUMMY">
                    <dummy-payment />
                </tab>

                <tab name="Tes2">
                    Hiwefsrgdtfhmj
                </tab>
            </tabs>
        </div>

    </page>
</template>

<script lang="ts">
    import Page from "./Page.vue";
    import Loader from "../misc/Loader.vue";
    import Error from "../misc/Error.vue";
    import MarkdownViewer from "../misc/MarkdownViewer.vue";
    import {Component, Vue, Watch} from "vue-property-decorator";
    import {Store} from "../../store";
    import {useStore} from "vuex-simple";
    import {dto} from "../../store/dto";
    import Tabs from "../Tabs.vue";
    import Tab from "../Tab.vue";
    import DummyPayment from "../payments/DummyPayment.vue";

    @Component({
        components: {DummyPayment, Tab, Tabs, MarkdownViewer, Error, Loader, Page}
    })
    export default class PurchaseCourse extends Vue {
        status: dto.EnrollmentStateDto = null;
        course: dto.CourseExDto = null;
        submitting = false;
        currentPath = location.pathname;

        store: Store = useStore(this.$store);

        dateString(v) { return new Date(v).toLocaleDateString() }

        canJoin(course: dto.CourseDto) {
            return course.available && !this.willBeAvailable(course) && !this.wasAvailable(course)
        }

        willBeAvailable(course: dto.CourseDto) {
            let now = +new Date();
            return course.requirements.signUp.beg && now < +new Date(course.requirements.signUp.beg)
        }

        wasAvailable(course: dto.CourseDto) {
            let now = +new Date();
            return course.requirements.signUp.end && now > +new Date(course.requirements.signUp.end)
        }

        hasPreview(course: dto.CourseExDto) {

        }

        async load() {
            this.course = await this.store.courses.get(+this.$route.params.id)
            this.status = await this.store.courses.status(+this.$route.params.id)
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
