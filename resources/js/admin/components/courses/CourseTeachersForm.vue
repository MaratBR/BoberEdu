<template>
    <div class="course-teachers">
        <div class="teacher" v-for="teacher in teachers">
            <div v-if="teacher.deleted" class="deleted">
                <i class="fa fa-info"></i>
                Teacher has been removed
                <button @click.prevent="teacher.deleted = false">Revert</button>
            </div>
            <template v-else>
                <div class="avatar s60">
                    <img :src="'/storage/' + teacher.avatar" alt="">
                </div>

                <div class="about">
                    <span>{{ teacher.fullName }}</span><br>
                    <small>ID: {{ teacher.id }}</small>
                </div>

                <div class="actions">
                    <a class="button" href="#" @click.prevent="teacher.deleted = false">
                        <i class="fa fa-trash"></i>
                        delete
                    </a>
                    <router-link class="button" :to="{name: 'admin__teacher_edit', params: {id: teacher.id}}">
                        <i class="fa fa-edit"></i>
                        edit
                    </router-link>
                </div>
            </template>
        </div>

        <button class="action" @click.prevent="addNew">
            <i class="fa fa-plus"></i>
            Assign new teacher
        </button>
    </div>
</template>

<script lang="ts">
    import {Vue, Component, Prop, dto, Watch} from "@common";
    import AdminSection from "@admin/components/layout/AdminSection.vue";

    type TeacherData = {
        id: number,
        avatar: string,
        fullName: string,
        deleted: boolean,
        new: boolean
    }

    @Component({
        name: "CourseTeachersForm",
        components: {AdminSection}
    })
    export default class CourseTeachersForm extends Vue {
        @Prop() course: dto.CourseExDto

        teachers: TeacherData[] = []
        inProgress = true;

        @Watch('course')
        onCourseChanged() {
            if (this.course === null) {
                this.teachers = []
            } else {
                this.teachers = this.course.teachers.map(t => ({
                    avatar: t.avatar,
                    fullName: t.fullName,
                    id: t.id,
                    new: false,
                    deleted: false
                }))
            }
        }

        addNew() {

        }
    }
</script>

<style scoped lang="scss">
    .teacher {
        display: flex;
        align-items: center;
        padding: 10px;

        &:hover {
            background: whitesmoke;

            & > .actions {
                display: block;
            }
        }

        & > .avatar {
            margin-right: 15px;
        }

        & > .about {
            flex-grow: 1;
        }

        & > .actions {
            display: none;
            padding: 10px;
        }

        &:not(:last-child) {
            border-bottom: 1px solid #aaa;
        }
    }

    .fa-info {
        color: #026aca;
    }
</style>
