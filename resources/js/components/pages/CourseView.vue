<template>
    <page v-if="course" :title="course.name">
        <template v-slot:header>
            <router-link class="btn" :to="{name: 'edit_course', params: {id: $route.params.id}}">Edit</router-link>
        </template>
        <div class="course">
            <main class="course__content">
                <editor-viewer :value="course.about" />
                <pre>{{course}}</pre>
            </main>
            <aside class="course__buy">
                <div class="course-aside">
                    <router-link class="btn btn--flat-white" to="/">Buy for ${{course.price}}</router-link>
                    <span v-if="hasFreePreview">or</span>
                    <router-link v-if="hasFreePreview" class="btn btn--flat-white" to="/">Try for free</router-link>
                </div>
            </aside>

            <aside class="course__units">
                <h2>Units</h2>
                <ul>
                    <li v-for="u in course.units" class="unit">
                        <router-link class="btn btn--transparent" :to="{name: 'unit', params: {id: u.id}}">{{u.name}}</router-link>
                        <span v-if="u.is_preview" class="unit__badge">preview</span>
                    </li>
                </ul>
            </aside>
        </div>
    </page>
    <page title="Loading..." v-else-if="loading">
        <loader />
    </page>
    <page v-else title="Not found">
        <error>Course not found</error>
    </page>
</template>

<script lang="ts">
    import Page from "./Page.vue";
    import {Course} from "../../apiDef";
    import {courses} from "../../api";
    import Loader from "../misc/Loader.vue";
    import Error from "../misc/Error.vue";
    import {EditorViewer} from '@toast-ui/vue-editor'

    export default {
        name: "CourseView",
        components: {Error, Loader, Page, EditorViewer},
        data() {
            return {
                course: null as Course | null,
                loading: true
            }
        },

        created(): void {
            this.init()
        },
        methods: {
            init() {
                if (isNaN(+this.$route.params.id)) {
                    this.course = null;
                    return;
                }
                this.loading = true;
                courses.get(this.$route.params.id)
                    .then(c => {
                        this.course = c
                    })
                    .finally(() => this.loading = false)
            }
        },
        computed: {
            hasFreePreview(): boolean {
                return this.course.units.some(u => u.is_preview)
            }
        },
        watch: {
            $route() {
                this.init()
            }
        }
    }
</script>

<style lang="sass" scoped>
    @import "resources/sass/lib/config"

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
