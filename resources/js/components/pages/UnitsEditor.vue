<template>
    <section class="units-editor">
        <div class="unit-entry-wrp" v-for="ui in indexes">
            <div class="unit-entry">
                <header class="unit-entry__header">
                    <label>
                        Name:
                        <input type="text" class="input" v-model="units[ui].name">
                    </label>
                </header>

                <div class="unit-entry__body">
                    <textarea class="input" v-model="units[ui].about" />
                </div>
            </div>

            <div class="unit-controls">
                <button v-if="indexes.indexOf(ui) !== 0" @click="move(ui, -1)" class="btn btn--transparent">
                    <i class="fa fa-arrow-up"></i>
                </button>
                <button v-if="indexes.indexOf(ui) !== indexes.length - 1" @click="move(ui, 1)" class="btn btn--transparent">
                    <i class="fa fa-arrow-down"></i>
                </button>
            </div>
        </div>
    </section>
</template>

<script lang="ts">
    import {Unit} from "../../apiDef";
    import Vue from "vue";

    export default {
        name: "UnitsEditor",
        data() {
            return {
                changes: {},
                indexes: []
            }
        },
        props: {
            units: {
                required: true,
                type: Array
            }
        },
        methods: {
            move(unitIndex: number, diff: number) {
                let ii = this.indexes.indexOf(unitIndex);
                let newIi = ii + diff;
                if (newIi < 0)
                    newIi = 0;
                else if (newIi >= this.indexes.length)
                    newIi = this.indexes.length - 1;
                if (newIi !== ii) {
                    let i1 = this.indexes[newIi], i2 = this.indexes[ii];
                    Vue.set(this.indexes, newIi, i2);
                    Vue.set(this.indexes, ii, i1);
                }
            },
        },
        created(): void {
            this.units.push({
                name: 'Test',
                order_num: 10,

            });
            this.indexes = Array.from(Array(this.units.length).keys())

        }
    }
</script>

<style lang="sass" scoped>
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
