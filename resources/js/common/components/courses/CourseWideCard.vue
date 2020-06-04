<template>
    <div class="course-w">
        {{ course }}
    </div>
</template>

<script lang="ts">
    import {Vue, Component, Prop, dto} from "@common";

    @Component({
        name: "CourseWideCard"
    })
    export default class CourseWideCard extends Vue {
        @Prop({type: Object, required: true}) course: dto.CoursePageItemDto
    }
</script>

<style scoped lang="scss">

</style>
