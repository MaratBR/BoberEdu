<template>
    <div class="container mt-4">
        <ul class="breadcrumb">
            <li class="breadcrumb-item">
                <router-link :to="{name: 'teacher_dashboard__edit', params: {id: courseId}}">
                    {{courseName}}
                </router-link>
            </li>
            <li class="breadcrumb-item">
                {{ unitName }} <small>(ID: {{unitId}})</small>
            </li>
            <li class="breadcrumb-item active">{{ isNew ? 'new' : title }}</li>
        </ul>

        <form action="#" @submit.prevent="submit">
            <input-text required label="Name" v-model="title" :disabled="saving" />
            <input-textarea required label="Summary" v-model="summary" :disabled="saving" />

            <markdown-editor v-model="content" theme="default" />
            <div class="mt-2">
                <button :disabled="saving" :class="saved ? 'btn-success' : 'btn-primary'"
                        class="btn save-btn d-flex align-items-center">
                    <template v-if="saving">
                        <i class="spinner-border spinner-border-sm mr-1"></i>
                        Saving...
                    </template>
                    <template v-else-if="saved">
                        <i class="fas fa-check mr-1"></i>
                        Saved
                    </template>
                    <template v-else>
                        Save
                    </template>
                </button>
            </div>
        </form>
    </div>
</template>

<script lang="ts">
    import {Vue, Component, Prop, dto} from "@common";
    import TeachersStoreComponent from "@teacher/components/TeachersStoreComponent";
    import InputText from "@common/components/forms/InputText.vue";
    import InputTextarea from "@common/components/forms/InputTextarea.vue";
    import LessonExDto = dto.LessonExDto;

    @Component({
        name: "LessonEditor",
        components: {InputTextarea, InputText}
    })
    export default class LessonEditor extends TeachersStoreComponent {
        @Prop() id: number;
        @Prop() unitId: number;

        loading = true
        saving = false
        actualUnitId: number = null
        courseId: number = null
        unitName: string = null
        courseName: string = null
        title: string = null
        content: string = null
        summary: string = null
        isNew = true;
        saved = false

        async load() {
            this.updateFrom(await this.teacher.getLesson(this.id))
        }

        updateFrom(lesson: LessonExDto) {
            this.courseName = lesson.courseName
            this.courseId = lesson.courseId
            this.actualUnitId = lesson.unitId
            this.unitName = lesson.unitName
            this.title = lesson.title
            this.summary = lesson.summary
            this.content = lesson.content
        }

        async submit() {
            this.saving = true

            if (this.isNew) {
                let {id} = await this.teacher.createLesson({
                    title: this.title,
                    unitId: this.actualUnitId,
                    summary: this.summary,
                    content: this.content
                })

                await this.$router.push({
                    name: 'teacher_dashboard__lesson_edit',
                    params: {
                        id: id+''
                    }
                })
            } else {
                let lesson = await this.teacher.updateLesson({
                    id: this.id,
                    data: {
                        title: this.title,
                        summary: this.summary,
                        content: this.content
                    }
                })
                this.updateFrom(lesson)
                this.saving = false
            }

            this.saved = true
            setTimeout(() => this.saved = false, 800)
        }

        created() {
            this.isNew = typeof this.id !== 'number' || isNaN(this.id)

            if (this.isNew) {
                this.loadCourseData()
            } else {
                this.load()
            }
        }

        private async loadCourseData() {
            let unit = await this.store.getUnit(this.unitId)
            this.courseName = unit.courseName
            this.courseId = unit.courseId
            this.actualUnitId = unit.id
            this.unitName = unit.name
        }
    }
</script>

<style scoped lang="scss">
    .save-btn {
        width: 120px;
        justify-content: center;
    }
</style>
