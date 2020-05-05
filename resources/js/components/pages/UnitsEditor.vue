<template>
    <form @submit.prevent="onSubmit" class="units-editor">
        <div class="units-editor__nounits" v-if="units.length == 0">
            <p class="p--middle text--hint p--10">No units yet, add one by click on button below</p>
        </div>
        <div v-else class="unit-entry-wrp" v-for="unit in units">
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

        <input class="btn btn--primary" :disabled="submitting" type="submit" value="Save">
        <error v-if="errors !== null" :error="errors" />
    </form>
</template>

<script lang="ts">
    import Vue from "vue";
    import Error from "../misc/Error.vue";

    export default {
        name: "UnitsEditor",
        components: {Error},
        data() {
            return {
                changes: {},
                unitsCollectionAdapter: undefined,
                units: [],
                submitting: false,
                errors: null
            }
        },
        props: {
            course: {
                required: true,
                type: Object
            }
        },
        methods: {
            move(unit, diff: number) {
                let index = this.units.indexOf(unit);
                let newIndex = index + diff;
                if (newIndex < 0 || newIndex >= this.units.length || newIndex === index)
                    return;
                this.units[index] = this.units[newIndex];
                Vue.set(this.units, newIndex, unit);
            },
            del(unit) {
                // Delete index from indexes
                this.unitsCollectionAdapter.delete(unit);
            },
            addNew() {

            },
            init() {

            },
            getPayload() {
                return {
                    new: this.unitsCollectionAdapter.created.map(u => u.getStagedChanges()),
                    upd: this.units.map(u => {
                        let changes = u.getStagedChanges();
                        if (Object.keys(changes).length === 0 || typeof changes.id !== 'number')
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
                this.$store.dispatch('courses/updateUnits', {course: this.course, data: this.getPayload()})
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
