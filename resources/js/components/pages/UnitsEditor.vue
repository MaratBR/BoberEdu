<template>
    <form @submit.prevent="onSubmit" class="units-editor">
        <div class="unit-entry-wrp" v-for="unit in units">
            <div class="unit-entry">
                <header class="unit-entry__header">
                    <label>
                        Name:
                        <input type="text" class="input" v-model="unit.name">
                    </label>
                </header>

                <div class="unit-entry__body">
                    <textarea class="input" v-model="unit.about" />
                </div>
            </div>


            <div class="unit-controls">
                <button v-if="true" @click.prevent="move(unit, -1)" class="btn btn--transparent">
                    <i class="fa fa-arrow-up"></i>
                </button>

                <button @click.prevent="del(unit)" class="btn btn--transparent">
                    <i class="fa fa-trash"></i>
                </button>

                <button v-if="true" @click.prevent="move(unit, 1)" class="btn btn--transparent">
                    <i class="fa fa-arrow-down"></i>
                </button>
            </div>
        </div>

        <div class="units-editor__add">
            <button class="btn" @click.prevent="addNew()">
                <i class="fa fa-plus"></i>
            </button>
        </div>

        <input :disabled="submitting" type="submit" value="Save">
        <error v-if="errors !== null" :error="errors" />
    </form>
</template>

<script lang="ts">
    import {Course, Unit} from "../../models";
    import Vue from "vue";
    import ModelCollection from "../../models/collection";
    import {IUnit} from "../../models/unit";
    import {courses, CreateUnitsRequest} from "../../api";
    import {PropValidator} from "vue/types/options";
    import Error from "../misc/Error.vue";

    export default {
        name: "UnitsEditor",
        components: {Error},
        data() {
            return {
                changes: {},
                unitsCollectionAdapter: undefined as ModelCollection<Unit, IUnit>,
                units: [] as Unit[],
                submitting: false,
                errors: null
            }
        },
        props: {
            course: {
                required: true,
                type: Object
            } as PropValidator<Course>
        },
        methods: {
            move(unit: Unit, diff: number) {
                let index = this.units.indexOf(unit);
                let newIndex = index + diff;
                if (newIndex < 0 || newIndex >= this.units.length || newIndex === index)
                    return;
                this.units[index] = this.units[newIndex];
                Vue.set(this.units, newIndex, unit);
            },
            del(unit: Unit) {
                // Delete index from indexes
                this.unitsCollectionAdapter.delete(unit);
            },
            addNew() {
                let unit = new Unit({});
                unit.enableStaging();
                this.unitsCollectionAdapter.add(unit);
            },
            init() {
                this.units = this.course.units;
                this.units.map(u => {
                    u.enableStaging();
                });
                this.unitsCollectionAdapter = new ModelCollection<Unit, IUnit>(this.units);
            },
            getPayload(): CreateUnitsRequest {
                return {
                    new: this.unitsCollectionAdapter.created.map(u => u.getStagedChanges()),
                    upd: this.units.map(u => {
                        let changes = u.getStagedChanges();
                        if (Object.keys(changes).length === 0)
                            return null;
                        changes.id = u.id;
                        return changes;
                    }).filter(u => u !== null),
                    delete: this.unitsCollectionAdapter.deleted.map(u => u.id),
                    order: this.units.map(u => u.id || `n${this.unitsCollectionAdapter.created.indexOf(u)}`)
                }
            },
            onSubmit() {
                this.submitting = true;
                courses
                    .createUnits(this.course, this.getPayload())
                    .catch(err => this.errors = err.response.data)
                    .finally(() => this.submitting = false)
            }
        },
        created(): void {
            this.init()
        }
    }
</script>

<style lang="sass" scoped>
    .units-editor
        &__add
            display: flex
            justify-content: center

            & > button
                width: 200px
                height: 50px
                font-size: 1.6em

    .unit-entry
        padding: 10px
        border: 1px solid whitesmoke
        background: whitesmoke

    .unit-entry-wrp
        display: grid
        grid-template-columns: 1fr 60px
        width: 100%
        margin-bottom: 20px

    .unit-controls
        display: flex
        flex-direction: column
        justify-content: space-evenly
</style>
