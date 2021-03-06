<template>
    <admin-section header="Units" :in-progress="inProgress">
        <error v-if="error" :error="error" />
        <form @submit.prevent="onSubmit" class="units-editor">
            <draggable v-model="units" handle=".handle" @end="onDragEnd">
                <div class="unit-item" :class="{changed: u.changed}" v-for="(u, index) in units" :key="index">
                    <div class="unit" v-if="!u.deleted">
                        <div class="input-group unit__name">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <label class="mb-0">
                                        Preview
                                        <input type="checkbox" v-model="u.preview" @change="updateChanged(u)" />
                                    </label>
                                </span>
                            </div>
                            <input required type="text" v-model="u.name" class="form-control"
                                   placeholder="Unit name" @input="updateChanged(u)" />
                        </div>

                        <div class="unit__act">
                            <button class="btn" @click.prevent="u.deleted = true"><i class="fas fa-trash"></i></button>
                            <button class="btn" @click.prevent="u.showLessons = !u.showLessons"><i class="fas fa-list"></i></button>
                        </div>

                        <div class="handle">
                            <i></i><i></i><i></i><i></i><i></i>
                        </div>
                        <textarea required @input="updateChanged(u)" class="unit__about form-control" v-model="u.about" />

                        <div class="unit__lessons" v-if="u.showLessons">
                            <ul>
                                <li v-for="l in u.lessons" :key="l.id">
                                    <router-link :to="{name: 'admin__lessons_edit', params: {id: l.id}}">{{ l.title }}</router-link>
                                </li>
                            </ul>

                            <div class="form-group">
                                <router-link class="button" v-if="u.lessons.length !== 0" :to="{name: 'admin__courses_edit_units', params: {id: course.id}}"><i class="fas fa-edit"></i> order</router-link>
                                <router-link class="button" :to="{name: 'admin__lessons_new', params: {id: u.id}}"><i class="fas fa-plus"></i> add</router-link>
                            </div>
                        </div>
                    </div>
                    <div class="deleted" v-else>
                        <i class="fas fa-info"></i>
                        <span>This unit has been deleted</span>
                        <button class="btn" @click.prevent="u.deleted = false">Restore</button>
                    </div>
                </div>
            </draggable>

            <div class="form-group">
                <button class="btn btn-light" @click.prevent="addUnit">
                    <i class="fas fa-plus"></i>
                    add
                </button>
            </div>

            <input type="submit" value="Update" class="btn btn-primary" :disabled="!changed">
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
    import InputCheckbox from "@common/components/forms/InputCheckbox.vue";

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
        components: {InputCheckbox, Error, AdminSection, draggable}
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
            let updated = this.units.filter(u => u.changed && !u.new).map(({id, name, preview, about}) => ({
                id,
                name,
                about,
                preview
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
            this.inProgress = true
            this.error = null
            try {
                await this.admin.updateCourseUnits({
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
