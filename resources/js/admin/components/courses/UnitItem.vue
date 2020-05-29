<template>

</template>

<script lang="ts">
    import {Vue, Component, Prop, dto} from "@common";

    @Component({
        name: "UnitItem"
    })
    export default class UnitItem extends Vue {
        @Prop() deleted: boolean;
        @Prop({required: true}) courseId: number;
        @Prop() unitId: number;
        @Prop({default: false}) last: boolean;
        @Prop({default: false}) first: boolean;
        @Prop({default: false}) preview: boolean;
        @Prop({default: false}) changed: boolean;
        @Prop({default: false}) about: string
        @Prop({default: false}) name: string
        @Prop({default: () => []}) lessons: dto.LessonDto[];

        showLessons: boolean = false;
    }
</script>

<style scoped lang="scss">



</style>
