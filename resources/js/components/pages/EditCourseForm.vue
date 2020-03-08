<template>
    <course-form v-if="course" :course="course"></course-form>
    <page v-else-if="loading" title="Loading">
        <loader />
    </page>
    <page v-else title="Not found">
        <error>Course not found</error>
    </page>
</template>

<script lang="ts">
    import CourseForm from "./CourseForm.vue";
    import {Course} from "../../apiDef";
    import Page from "./Page.vue";
    import Loader from "../misc/Loader.vue";
    import Error from "../misc/Error.vue";
    export default {
        name: "EditCourseForm",
        components: {Error, Loader, Page, CourseForm},
        data() {
            return {
                course: null as Course | null,
                loading: true
            }
        },
        methods: {
            init() {
                if (isNaN(+this.$route.params.id)) {
                    this.course = null;
                    return
                }
                this.loading = true;

                this.$store.dispatch('getCourse', +this.$route.params.id)
                    .then(c => this.course = c)
                    .finally(() => this.loading = false)

            }
        },
        watch: {
            $route() {
                this.init()
            }
        },
        created(): void {
            this.init()
        }
    }
</script>

<style scoped>

</style>
