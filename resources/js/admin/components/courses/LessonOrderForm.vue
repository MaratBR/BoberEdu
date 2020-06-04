<template>
    <admin-section :in-progress="inProgress">
        <template v-slot:header v-if="units">
            <ul class="breadcrumb">
                <li>Courses</li>
                <li><router-link :to="{name: 'admin__course_edit', params: {id: courseId}}">{{ courseName }}</router-link></li>
                <li>Edit: Structure</li>
            </ul>
        </template>

        <form @submit.prevent="onSubmit">
            <error :error="error" v-if="error" />
            <loader v-if="!units" />
            <unit-lessons-order
                v-else v-for="unit in units" :key="unit.id" :name="unit.name" :selected="selectedId === unit.id"
                :unit="unit" @changed="unit.changed = $event" @newOrder="unit.order = $event" />

            <div class="control">
                <input type="submit" value="Save">
            </div>
        </form>
    </admin-section>
</template>

<script lang="ts">
    import {Vue, Component, dto, Prop, requests} from "@common";
    import AdminSection from "@admin/components/layout/AdminSection.vue";
    import AdminStoreComponent from "@admin/components/AdminStoreComponent";
    import Loader from "@common/components/utils/Loader.vue";
    import UnitLessonsOrder from "@admin/components/courses/UnitLessonsOrder.vue";
    import {getError} from "@common/utils";
    import Error from "@common/components/utils/Error.vue";

    type UnitData = {
        id: number,
        name: string,
        lessons: dto.LessonDto[],
        order: number[] | null,
        changed: boolean
    }

    @Component({
        name: "LessonOrderForm",
        components: {Error, UnitLessonsOrder, Loader, AdminSection}
    })
    export default class LessonOrderForm extends AdminStoreComponent {
        @Prop({ required: true }) courseId: number;
        @Prop({ required: true }) selectedId: number;
        units: UnitData[] = []
        courseName: string = null
        inProgress = false;
        error = null;

        async load() {
            let d = await this.store.courses.getUnits(this.courseId)
            this.courseName = d.courseName
            this.units = d.units.map(u => ({
                id: u.id,
                name: u.name,
                lessons: u.lessons.map(l => ({
                    id: l.id,
                    title: l.title,
                    summary: l.summary
                })),
                order: null,
                changed: false
            }))
        }

        created() {
            this.load()
        }

        async onSubmit() {
            let r: requests.UpdateLessonsOrder = {
                units: this.units.filter(u => u.changed).map(u => ({
                    id: u.id,
                    order: u.order
                }))
            }

            if (r.units.length === 0)
                return

            this.inProgress = false
            try {
                await this.admin.updateLessonsOrder({
                    id: this.courseId,
                    data: r
                })
                await this.load()
            } catch (e) {
                this.error = getError(e)
            } finally {
                this.inProgress = false
            }
        }
    }
</script>

<style scoped lang="scss">

</style>
