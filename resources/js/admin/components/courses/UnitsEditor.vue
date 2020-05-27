<template>
    <admin-section header="Units" :in-progress="inProgress">
        <error v-if="error" :error="error" />
        <form @submit.prevent="onSubmit" class="units-editor">
            <draggable v-model="units" handle=".handle" @end="onDragEnd">
                <transition-group>
                    <unit-item :key="u.unit.id" :unit="u.unit" :deleted="u.deleted" v-for="u in units"
                               @delete="u.deleted = true" @restore="u.deleted = false" :changed="u.changed"
                               @change="updateChanged(u)" />
                </transition-group>
            </draggable>

            <input type="submit" value="Update" :disabled="!changed">
        </form>
    </admin-section>
</template>

<script lang="ts">
    import {Vue, Component, Prop, dto, Watch, requests} from "@common";
    import UnitItem from "@admin/components/courses/UnitItem.vue";
    import draggable from 'vuedraggable'
    import AdminSection from "@admin/components/layout/AdminSection.vue";
    import AdminStoreComponent from "@admin/components/AdminStoreComponent";
    import Error from "@common/components/utils/Error.vue";
    import {getError} from "@common/utils";

    type Unit = {
        unit: dto.UnitExDto,
        deleted: boolean,
        changed: boolean,
        new: boolean
    }

    @Component({
        name: "UnitsEditor",
        components: {Error, AdminSection, UnitItem, draggable}
    })
    export default class UnitsEditor extends AdminStoreComponent {
        units: Unit[] = null
        inProgress = false;
        error = null;

        @Prop() course: dto.CourseExDto;

        get changed() {
            return this.units.some(u => u.changed)
        }

        created() {
            this.onCourseChanged()
        }

        @Watch('course')
        onCourseChanged() {
            this.units = this.course.units.map(unit => ({
                unit: {
                    name: unit.name,
                    id: unit.id,
                    lessons: unit.lessons,
                    about: unit.about,
                    preview: unit.preview
                },
                deleted: false,
                changed: false,
                new: false
            }))
        }

        updateChanged(u: Unit) {
            u.changed = this.isChanged(u)
        }

        isChanged(u: Unit): boolean {
            if (u.new)
                return true
            let pos = this.units.indexOf(u)
            let original = this.course.units.find(({id}) => id == u.unit.id)
            let oldPos = this.course.units.indexOf(original)
            if (pos !== oldPos)
                return true
            return original.name !== u.unit.name || original.preview !== u.unit.preview
                || original.about !== u.unit.about;
        }

        onDragEnd(e) {
            let i1 = e.newIndex, i2 = e.oldIndex;
            if (i1 > i2) {
                let tmp = i1
                i1 = i2;
                i2 = tmp;
            }
            for (let i = i1; i <= i2; i++) {
                this.updateChanged(this.units[i])
            }
        }

        getRequest(): requests.UpdateCourseUnits {
            let updated = this.units.filter(u => u.changed && !u.new).map(({unit}) => ({
                id: unit.id,
                name: unit.name,
                about: unit.about
            }))
            let created: requests.NewUnitPayload[] = this.units.filter(u => u.new).map(({unit}) => ({
                name: unit.name,
                about: unit.about,
                preview: unit.preview
            }))
            let deleted: number[] = this.course.units.filter(
                u => !this.units.find(o => !o.deleted && o.unit.id == u.id)
            ).map(u => u.id)

            let ii = 0;
            let order: string[] = this.units.filter(u => !u.deleted).map(u => u.new ? `n${ii++}` : u.unit.id+'')

            return {
                new: created,
                upd: updated,
                delete: deleted,
                order
            }
        }

        async onSubmit() {
            let r = this.getRequest()

            this.inProgress = true
            this.error = null
            try {
                await this.admin.courses.updateUnits({
                    id: this.course.id,
                    data: r
                })
                this.$emit('saved')
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
