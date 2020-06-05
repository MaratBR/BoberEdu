<template>
    <admin-section header="Teachers" :in-progress="inProgress">
        <form class="course-teachers" @submit.prevent="submit">
            <div class="course-teachers__list" v-show="!searching">
                <div class="teacher" v-for="teacher in teachers">
                    <div v-if="teacher.deleted" class="deleted">
                        <i class="fas fa-info"></i>
                        Teacher has been removed
                        <button class="btn" @click.prevent="teacher.deleted = false">Revert</button>
                        <input-textarea required v-model="teacher.assignmentReason"
                                        placeholder="Please, provide reason for revoking teacher's permissions" />
                    </div>
                    <template v-else>
                        <div class="rounded-circle img-thumbnail">
                            <img :src="teacher.avatar" alt="">
                        </div>

                        <div class="about">

                            <span>{{ teacher.fullName }}</span>
                            <br>
                            <small>ID: {{ teacher.id }}</small><br>
                            <small v-if="teacher.new">You assigned this teacher</small>
                            <input-textarea v-if="teacher.new" v-model="teacher.assignmentReason" required
                                            placeholder="Please, provide reason for this assignment" />
                        </div>

                        <div class="actions">
                            <a class="button" href="#" @click.prevent="teacher.deleted = true">
                                <i class="fas fa-trash"></i>
                                delete
                            </a>
                            <router-link class="button" :to="{name: 'admin__teacher_edit', params: {id: teacher.id}}">
                                <i class="fas fa-edit"></i>
                                edit
                            </router-link>
                        </div>
                    </template>
                </div>
                <br>
                <button class="btn" @click.prevent="searching = true">
                    <i class="fas fa-plus"></i>
                    Assign new teacher
                </button>
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-save"></i>
                    Save
                </button>
            </div>
            <template v-if="searching">
                <button class="btn" @click.prevent="searching = false"><i class="fas fa-arrow-left"></i> back</button>
                <span>Chose a teacher you want to assign</span>
                <teachers-search @selected="addNew($event)" selectable :filter="(t) => teachers.every(at => at.id !== t.id)" />
            </template>

            <error :error="error" v-if="error" />
        </form>
    </admin-section>
</template>

<script lang="ts">
    import {Vue, Component, Prop, dto, Watch} from "@common";
    import AdminSection from "@admin/components/layout/AdminSection.vue";
    import TeachersSearch from "@admin/components/teachers/TeachersSearch.vue";
    import AdminStoreComponent from "@admin/components/AdminStoreComponent";
    import InputTextarea from "@common/components/forms/InputTextarea.vue";
    import {getError} from "@common/utils";
    import Error from "@common/components/utils/Error.vue";

    type TeacherData = {
        id: number,
        avatar: string,
        fullName: string,
        deleted: boolean,
        new: boolean,
        assignmentReason: string | null
    }

    @Component({
        name: "CourseTeachersForm",
        components: {Error, InputTextarea, TeachersSearch, AdminSection}
    })
    export default class CourseTeachersForm extends AdminStoreComponent {
        @Prop() course: dto.CourseExDto

        teachers: TeacherData[] = []
        inProgress = false;
        searching = false
        error = null;

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
                    deleted: false,
                    assignmentReason: null
                }))
            }
        }

        addNew(teacher: dto.TeacherDto) {
            this.teachers.push({
                new: true,
                deleted: false,
                id: teacher.id,
                fullName: teacher.fullName,
                avatar: teacher.avatar,
                assignmentReason: ''
            })
            this.searching = false
        }

        async submit() {
            this.inProgress = true
            let promises: Promise<any>[] = []
            let assigned = this.teachers.filter(t => t.new)
            let revoked = this.teachers.filter(t => t.deleted)

            for (let t of assigned) {
                promises.push(
                    this.admin.assignTeacher({
                        teacherId: t.id,
                        courseId: this.course.id,
                        reason: t.assignmentReason
                    })
                )
            }

            for (let t of revoked) {
                promises.push(
                    this.admin.revokeTeacher({
                        teacherId: t.id,
                        courseId: this.course.id,
                        reason: t.assignmentReason
                    })
                )
            }

            try {
                await Promise.all(promises)
                this.$emit('updated')
            } catch (e) {
                this.error = getError(e)
            } finally {
                this.inProgress = false
            }
        }
    }
</script>

<style scoped lang="scss">
    .deleted {
        flex-grow: 1;
    }

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
