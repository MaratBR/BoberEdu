<template>
    <admin-section header="Units" :in-progress="inProgress">
        <error v-if="error" :error="error" />
        <form @submit.prevent="onSubmit" class="units-editor">
            <draggable v-model="units" handle=".handle" @end="onDragEnd">
                <div class="unit-item" :class="{changed: u.changed}" v-for="(u, index) in units" :key="index">
                    <div class="unit" v-if="!u.deleted">
                        <input required @input="$emit('change')" type="text" v-model="u.name" class="input unit__name"
                               placeholder="Unit name" />

                        <div class="unit__act">
                            <button @click.prevent="$emit('delete')"><i class="fa fa-trash"></i></button>
                            <button :disabled="u.lessons.length === 0"
                                    @click.prevent="u.showLessons = !u.showLessons"><i class="fa fa-list"></i></button>
                        </div>

                        <div class="handle">
                            <i></i><i></i><i></i><i></i><i></i>
                        </div>
                        <textarea required @input="$emit('change')" class="unit__about input" v-model="u.about" />

                        <div class="unit__lessons" v-if="u.showLessons">
                            <ul>
                                <li v-for="l in u.lessons" :key="l.id">
                                    <router-link :to="{name: 'admin__lessons_edit', params: {id: l.id}}">{{ l.title }}</router-link>
                                </li>
                            </ul>

                            <div class="control">
                                <router-link class="button" :to="{name: 'admin__courses_edit_units', params: {id: course.id}}"><i class="fa fa-edit"></i> order</router-link>
                                <router-link class="button" :to="{name: 'admin__lessons_new', params: {id: u.id}}"><i class="fa fa-plus"></i> add</router-link>
                            </div>
                        </div>
                    </div>
                    <div class="deleted" v-else>
                        <i class="fa fa-info"></i>
                        <span>This unit has been deleted</span>
                        <button @click.prevent="$emit('restore')">Restore</button>
                    </div>
                </div>
            </draggable>

            <div class="control">
                <button @click.prevent="addUnit">
                    <i class="fa fa-plus"></i>
                    add
                </button>
            </div>

            <input type="submit" value="Update" :disabled="!changed">
        </form>
    </admin-section>
</template>

<script lang="ts">
    import {Vue, Component, Prop, dto, Watch, requests} from "@common";
    import draggable from 'vuedraggable'
    import AdminSection from "@admin/components/layout/AdminSection.vue";
    import AdminStoreComponent from "@admin/components/AdminStoreComponent";
    import Error from "@common/components/utils/Error.vue";
    import {getError} from "@common/utils";

    type Unit = {
        id?: number
        name: string,
        about: string,
        preview: boolean,
        lessons: dto.LessonDto[]
        deleted: boolean,
        changed: boolean,
        new: boolean,
        showLessons: boolean
    }

    @Component({
        name: "UnitsEditor",
        components: {Error, AdminSection, draggable}
    })
    export default class UnitsEditor extends AdminStoreComponent {
        units: Unit[] = null
        inProgress = false;
        error = null;

        @Prop() course: dto.CourseExDto;

        get changed() {
            return this.units.some(u => u.changed)
        }

        addUnit() {
            this.units.push({
                new: true,
                name: '',
                about: '',
                preview: false,
                changed: true,
                lessons: [],
                deleted: false,
                showLessons: false
            })
        }

        created() {
            this.onCourseChanged()
        }

        @Watch('course')
        onCourseChanged() {
            this.units = this.course.units.map(unit => ({
                name: unit.name,
                id: unit.id,
                lessons: unit.lessons,
                about: unit.about,
                preview: unit.preview,
                deleted: false,
                changed: false,
                new: false,
                showLessons: false
            }))
        }

        updateChanged(u: Unit) {
            u.changed = this.isChanged(u)
        }

        isChanged(u: Unit): boolean {
            if (u.new)
                return true
            let pos = this.units.indexOf(u)
            let original = this.course.units.find(({id}) => id == u.id)
            let oldPos = this.course.units.indexOf(original)
            if (pos !== oldPos)
                return true
            return original.name !== u.name || original.preview !== u.preview
                || original.about !== u.about;
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
            let updated = this.units.filter(u => u.changed && !u.new).map(({id, name, about}) => ({
                id,
                name,
                about
            }))
            let created: requests.NewUnitPayload[] = this.units.filter(u => u.new).map(({preview, name, about}) => ({
                name,
                about,
                preview
            }))
            let deleted: number[] = this.course.units.filter(
                u => !this.units.find(o => !o.deleted && !o.new && o.id == u.id)
            ).map(u => u.id)

            let ii = 0;
            let order: string[] = this.units.filter(u => !u.deleted).map(u => u.new ? `n${ii++}` : u.id+'')

            return {
                new: created,
                upd: updated,
                delete: deleted,
                order
            }
        }

        async onSubmit() {
            let r = this.getRequest()
            debugger
            this.inProgress = true
            this.error = null
            try {
                debugger
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
    .unit-item {
        border-bottom: 1px solid #aaa;
        border-left: 2px solid transparent;
        margin-bottom: 10px;

        &.sortable-chosen > .unit {
            background: #ededef;
        }

        &.changed {
            border-left-color: #00a6f9;
        }
    }

    .unit {
        display: grid;
        grid-gap: 5px;
        grid-template: auto auto 1fr / 25px 1fr 60px;
        padding: 10px;

        button {
            border: none;
            background: none;
            color: #555;
            outline: none;

            &:disabled {
                background: none;
                opacity: 0.5;
            }

            &:hover {
                background: rgba(black, 0.05);
                color: black;
            }
        }

        .deleted {
            margin: 15px 30px;
            & > i {
                color: #00a6f9;
            }
        }


        & > .handle {
            grid-area: 1 / 1 / 3 / 2;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;

            & > i {
                display: block;
                height: 2px;
                width: 20px;
                background: gray;
                &:not(:last-child) {
                    margin-bottom: 5px;
                }
            }
        }

        &__act {
            grid-area: 1 / 3 / 3 / 4;
            display: flex;
            flex-direction: column;
            justify-content: stretch;

            & > * {
                flex-grow: 1;
            }
        }

        &__about {
            grid-area: 2 / 2 / 3 / 3;
        }
        &__name {
            grid-area: 1 / 2 / 2 / 3;
        }
        &__lessons {
            grid-area: 3 / 2 / 4 / 4;
        }

        .fa-trash {
            color: red;
        }
    }


</style>
