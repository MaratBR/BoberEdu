<template>
    <div class="lesson container">
        <div class="hero--phead">
            {{ lesson }}
            {{ courseId }}
            <router-link :to="{name: 'lesson', params: {v: courseId + '_' + (lessonId + 1)}}">1234</router-link>
        </div>
    </div>
</template>

<script lang="ts">
    import {Component, Prop, Vue, Watch} from "vue-property-decorator";
    import {dto} from "../../store/dto";
    import LessonExDto = dto.LessonExDto;
    import {useStore} from "vuex-simple";
    import {Store} from "../../store";

    @Component
    export default class extends Vue {
        @Prop({ required: true }) lessonId: number;
        @Prop({ required: true }) courseId: number;

        lesson: LessonExDto = null;
        store = useStore<Store>(this.$store)

        @Watch('lessonId')
        async onLessonIdUpdated() {
            this.lesson = await this.store.lessons.get(this.lessonId)
        }

        created() {
            this.onLessonIdUpdated()
        }
    }
</script>

<style scoped lang="scss">

</style>
