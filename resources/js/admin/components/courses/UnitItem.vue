<template>
    <div class="unit-item" :class="{changed}">
        <div class="unit" v-if="!deleted">
            <input @input="$emit('change')" type="text" v-model="unit.name" class="input unit__name" />

            <div class="unit__act">
                <button @click.prevent="$emit('delete')"><i class="fa fa-trash"></i></button>
                <button :disabled="unit.lessons.length === 0" @click.prevent="showLessons = !showLessons"><i class="fa fa-list"></i></button>
            </div>

            <div class="handle">
                <i></i><i></i><i></i><i></i><i></i>
            </div>
            <textarea @input="$emit('change')" class="unit__about input" v-model="unit.about" />

            <div class="unit__lessons" v-if="showLessons">
                <ul>
                    <li v-for="l in unit.lessons" :key="l.id">
                        {{ l.title }}
                    </li>
                </ul>

                <div>

                </div>
            </div>
        </div>
        <div class="deleted" v-else>
            <i class="fa fa-info"></i>
            <span>This unit has been deleted</span>
            <button @click.prevent="$emit('restore')">Restore</button>
        </div>
    </div>
</template>

<script lang="ts">
    import {Vue, Component, Prop, dto} from "@common";

    @Component({
        name: "UnitItem"
    })
    export default class UnitItem extends Vue {
        @Prop() unit: dto.UnitExDto;
        @Prop() deleted: boolean;
        @Prop({default: false}) last: boolean;
        @Prop({default: false}) first: boolean;
        @Prop({default: false}) changed: boolean;

        showLessons: boolean = false;
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

    .deleted {
        margin: 15px 30px;
        & > i {
            color: #00a6f9;
        }
    }

    .unit {
        display: grid;
        grid-gap: 5px;
        grid-template: auto auto 1fr / 25px 1fr 60px;
        padding: 10px;


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
    }

    .fa-trash {
        color: red;
    }

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
</style>
