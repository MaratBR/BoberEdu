<template>
    <loader no-value-message="Course not found" :promise="promise" v-slot="{value}">
        <page :title="value.name">
            <template v-slot:header>
                <router-link class="btn" :to="{name: 'edit_course', params: {id: $route.params.id}}">Edit</router-link>
            </template>
            <div class="course">
                <main class="course__content">
                    <markdown-viewer :value="value.about" />
                    <pre>{{value}}</pre>
                </main>
                <aside class="course__buy">
                    <div class="course-aside">
                        <router-link class="btn btn--flat-white" :to="{name: 'purchase_course', params: {id: value.id}}">Buy for ${{value.price}}</router-link>
                        <span v-if="hasFreePreview(value)">or</span>
                        <router-link v-if="hasFreePreview(value)" class="btn btn--flat-white" to="/">Try for free</router-link>
                    </div>
                </aside>

                <aside class="course__units">
                    <h2>Units</h2>
                    <ul>
                        <li v-for="u in value.units" class="unit">
                            <span>{{u.name}}</span>
                            <span v-if="u.is_preview" class="unit__badge">preview</span>

                            <ul>
                                <li v-for="l in u.lessons">{{l}}</li>
                            </ul>
                        </li>
                    </ul>
                </aside>
            </div>
        </page>
    </loader>
</template>

<script lang="ts">
    import Page from "./Page.vue";
    import Loader from "../misc/Loader.vue";
    import Error from "../misc/Error.vue";
    import MarkdownViewer from "../misc/MarkdownViewer.vue";
    import {Component, Vue, Watch} from "vue-property-decorator";
    import {Course} from "../../store/modules/CoursesModule";
    import {Store} from "../../store";
    import {useStore} from "vuex-simple";

    @Component({
        components: {MarkdownViewer, Error, Loader, Page}
    })
    export default class CourseView extends Vue {
        courseId: number | null = null;
        promise: Promise<Course> | null = null;
        store: Store = useStore(this.$store);

        created(): void {
            this.init()
        }

        init() {
            this.courseId = +this.$route.params.id || null;
            this.promise = this.store.courses.get(this.courseId)
        }

        hasFreePreview(course): boolean {
            return course.units.some(u => u.is_preview)
        }

        @Watch('$route')
        routeChanged() {
            this.init();
        }
    }
</script>

<style lang="sass" scoped>
    @import "../../../sass/lib/config"

    .unit
        &__badge
            background: whitesmoke
            padding: 6px
            font-size: 0.8em
            font-variant: all-small-caps

    .course-aside
        background: linear-gradient(133deg, rgba(244,101,200,1) 4%, rgba(135,131,245,1) 100%)
        border-radius: 0.7em
        min-height: 100px
        color: white
        display: flex
        justify-content: center
        align-items: center
        box-sizing: border-box
        & > span
            font-size: 90%
            margin: 10px


    .course
        display: grid
        grid-template-columns: 0.66666fr 0.33333fr
        grid-template-rows: auto 1fr
        &__content
            min-width: 0 // grid blowout fix https://stackoverflow.com/questions/43311943/prevent-content-from-expanding-grid-items
            padding-right: 10px
            grid-row: 1/span 2

        &__units
            grid-row: 2

        @media ($ss-breakpoint-tablet)
            grid-template-rows: 100px auto minmax(0, 1fr)
            grid-template-columns: 1fr

            &__buy
                grid-row: 1
            &__content
                grid-row: 2
            &__units
                grid-row: 3
</style>
