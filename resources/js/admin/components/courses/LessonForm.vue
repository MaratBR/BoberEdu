<template>
    <admin-section :in-progress="inProgress">
        <template v-slot:header>
            <ul class="breadcrumb">
                <li><router-link :to="{name: 'admin__courses'}">Courses</router-link></li>
                <li>
                    <router-link :to="{name: 'admin__courses_edit', params: {id: courseId}}">
                        {{ courseName }}
                    </router-link>
                </li>
                <li>
                    {{ courseName }}
                    <small>(ID: {{ unitId }})</small>
                </li>
                <li>{{ isNew ? 'new' : title }} <small v-if="!isNew">(ID: {{ id }})</small></li>
            </ul>
        </template>

        <form @submit.prevent="submit">
            <input-text required label="Name" v-model="title" />
            <input-textarea required hint="In a few words: what is this about" label="Summary" v-model="summary" />

            <markdown-editor required label="Lesson content" v-model="content" />
            <error :error="error" v-if="error" />

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
    import Error from "@common/components/utils/Error.vue";

    @Component({
        name: "LessonsForm",
        components: {Error, MarkdownEditor, InputTextarea, InputText, AdminSection}
    })
    export default class LessonForm extends AdminStoreComponent {
        @Prop() id: number;
        @Prop({ default: null }) unitId: number;

        unitName: string = null;
        courseName: string = null;
        courseId: number = null;
        content: string = null;
        title: string = null;
        summary: string = null;
        inProgress: boolean = false;
        error = null;
        isNew = false;

        @Watch('id')
        async load() {
            if (this.isNew) {
                let unit = await this.admin.getUnit(this.unitId)
                this.unitName = unit.name
                this.unitId = unit.id
                this.courseId = unit.courseId
                this.courseName = unit.courseName
            } else {
                this.update(await this.admin.lessons.get(this.id))
            }
        }

        update(lesson: dto.LessonExDto) {
            this.unitName = lesson.unitName
            this.unitId = lesson.id
            this.courseId = lesson.courseId
            this.courseName = lesson.courseName
            this.content = lesson.content
            this.title = lesson.title
            this.summary = lesson.summary
        }

        async submit() {
            let promise: Promise<dto.LessonExDto> = null

            if (this.isNew) {
                let r: requests.CreateLesson = {
                    title: this.title,
                    summary: this.summary,
                    content: this.content,
                    unitId: this.unitId
                }
                promise = this.admin.lessons.create(r)
            } else {
                let r: requests.UpdateLesson = {
                    title: this.title,
                    summary: this.summary,
                    content: this.content
                }
                promise = this.admin.lessons.update({id: this.id, data: r})
            }

            this.inProgress = true
            try {
                let lesson = await promise

                if (this.isNew) {
                    await this.$router.replace({
                        name: 'admin__lessons_edit',
                        params: {
                            id: lesson.id+''
                        }
                    })
                } else {
                    this.update(lesson)
                }
            } catch (e) {
                this.error = getError(e)
            } finally {
                this.inProgress = false
            }
        }

        created() {
            if (typeof this.id !== 'number') {
                this.isNew = true
            }
            this.load()
        }
    }
</script>

<style scoped lang="scss">

</style>
