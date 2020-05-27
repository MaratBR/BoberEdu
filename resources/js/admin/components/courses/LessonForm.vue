<template>
    <admin-section :in-progress="inProgress">
        <template v-slot:header>
            <ul class="breadcrumb">
                <li><router-link :to="{name: 'admin__courses'}">Courses</router-link></li>
                <li>{{ unitName }}</li>
                <li>{{ title }} <small>(ID: {{ id }})</small></li>
            </ul>
        </template>

        <form @submit.prevent="submit">
            <input-text label="Name" v-model="title" />
            <input-textarea hint="In a few words: what is this about" label="Summary" v-model="summary" />

            <markdown-editor label="Lesson content" v-model="content" />

            <div class="control">
                <input type="submit" value="Save">
            </div>
        </form>
    </admin-section>
</template>

<script lang="ts">
    import {Component, dto, Prop, requests, Watch} from "@common";
    import AdminStoreComponent from "@admin/components/AdminStoreComponent";
    import AdminSection from "@admin/components/layout/AdminSection.vue";
    import {InputText, InputTextarea} from "@common/components/forms";
    import MarkdownEditor from "@common/components/forms/MarkdownEditor.vue";
    import {getError} from "@common/utils";

    @Component({
        name: "LessonsForm",
        components: {MarkdownEditor, InputTextarea, InputText, AdminSection}
    })
    export default class LessonForm extends AdminStoreComponent {
        @Prop({ required: true }) id: number;

        unitName: string = null;
        unitId: number = null;
        courseName: string = null;
        courseId: number = null;
        content: string = null;
        title: string = null;
        summary: string = null;
        inProgress: boolean = false;
        error = null;

        @Watch('id')
        async load() {
            this.update(await this.admin.lessons.get(this.id))
        }

        update(lesson: dto.LessonExDto) {
            this.unitId = lesson.unitId
            this.unitName = lesson.unitName
            this.courseId = lesson.courseId
            this.courseName = lesson.courseName
            this.content = lesson.content
            this.title = lesson.title
            this.summary = lesson.summary
        }

        async submit() {
            let r: requests.UpdateLesson = {
                title: this.title,
                summary: this.summary,
                content: this.content
            }

            this.inProgress = true
            try {
                let lesson = await this.admin.lessons.update({id: this.id, data: r})
                this.update(lesson)
            } catch (e) {
                this.error = getError(e)
            } finally {
                this.inProgress = false
            }
        }

        created() {
            this.load()
        }
    }
</script>

<style scoped lang="scss">

</style>
