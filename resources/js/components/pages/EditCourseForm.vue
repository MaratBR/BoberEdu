<template>
    <loader :promise="promise" v-slot="{value}">
        <course-form :course="value"></course-form>
    </loader>
</template>

<script lang="ts">
    import CourseForm from "./CourseForm.vue";
    import Page from "./Page.vue";
    import Loader from "../misc/Loader.vue";
    import Error from "../misc/Error.vue";
    import {Component, Vue, Watch} from "vue-property-decorator";
    import {Course, CourseEx} from "../../store/modules/CoursesModule";
    import {Store} from "../../store";
    import {useStore} from "vuex-simple";


    @Component({
        components: {Error, Loader, Page, CourseForm}
    })
    export default class EditCourseForm extends Vue {

        promise: Promise<CourseEx>;
        store: Store = useStore(this.$store);

        init() {
            if (isNaN(+this.$route.params.id)) {
                this.promise = Promise.reject('Invalid ID: ' + this.$route.params.id);
                return;
            }

            this.promise = this.store.courses.get(+this.$route.params.id)
        }

        @Watch('$route')
        routeChanged() {
            this.init()
        }

        created(): void {
            this.init()
        }
    }
</script>

<style scoped>

</style>
