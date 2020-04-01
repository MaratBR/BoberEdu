<template>
    <loader :promise="promise" v-slot="{value}">
        <page :title="value.name">
            <units-editor :course="value" />
        </page>
    </loader>
</template>

<script>
    import Page from "./Page";
    import Loader from "../misc/Loader";
    import UnitsEditor from "./UnitsEditor";
    export default {
        name: "CourseUnitsForm",
        components: {UnitsEditor, Loader, Page},
        data() {
            return {
                promise: null
            }
        },
        created() {
            this.promise = this.$store.dispatch('courses/getCourse', this.$route.params.id)
        }
    }
</script>

<style scoped>

</style>
