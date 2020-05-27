<template>
    <div class="unit-lessons" :class="{selected}">
        <div class="unit-lessons__title" @click.prevent="expanded = !expanded">
            <span>{{ unit.name }} <small>(ID: {{unit.id}})</small></span>
            <i class="fa fa-chevron-down" :class="{r: expanded}"></i>
        </div>

        <div class="unit-lessons__lessons" v-show="expanded" >

            <draggable v-model="lessons" handle=".handle" @end="onDragEnd" v-if="lessons.length">
                <div class="lesson" v-for="l in lessons">
                    <div class="handle">
                        <i></i><i></i><i></i>
                    </div>
                    <span>{{ l.l.title }} <small>(ID: {{l.l.id}})</small></span>
                    <i class="changed-badge fa fa-circle" v-if="l.changed"></i>
                </div>
            </draggable>
            <p v-else>No lessons in this unit</p>

        </div>
    </div>
</template>

<script lang="ts">
    import {Vue, Component, Prop, dto, Watch} from "@common";
    import draggable from "vuedraggable";

    type LessonData = {
        l: dto.LessonDto,
        changed: boolean
    }

    @Component({
        name: "UnitLessonsOrder",
        components: {draggable}
    })
    export default class UnitLessonsOrder extends Vue {
        @Prop({required: true}) unit: dto.UnitExDto
        @Prop({default: false}) selected: boolean
        expanded = false;
        lessons: LessonData[] = []

        @Watch('selected')
        onSelectedChanged() {
            if (this.selected)
                this.expanded = true
        }

        @Watch('unit')
        init() {
            if (this.unit)
                this.lessons = this.unit.lessons.map(l => ({l, changed: false}))
        }

        created() {
            this.onSelectedChanged()
            this.init()
        }

        onDragEnd(e) {
            let i1 = e.oldIndex, i2 = e.newIndex;
            if (i1 === i2)
                return;
            if (i1 > i2) {
                let t = i1
                i1 = i2;
                i2 = t;
            }

            for (let i = i1; i <= i2; i++) {
                let lesson = this.lessons[i]
                lesson.changed = this.unit.lessons.findIndex(l => l.id == lesson.l.id) !== i
            }

            this.$emit('changed', this.lessons.some(l => l.changed))
            this.$emit('newOrder', this.lessons.map(l => l.l.id))
        }
    }
</script>

<style scoped lang="scss">
    .unit-lessons {
        padding-bottom: 15px;

        &.selected > &__title {
            background: #ededef;
        }

        &__title {
            display: flex;
            padding: 7px;
            border: 1px solid #ededef;

            &:hover {
                border-color: #dddddf;
                cursor: pointer;
            }

            :first-child {
                flex-grow: 1;
            }
        }

        &__lessons {
            padding: 0 7px;
        }
    }

    .lesson {
        display: flex;
        padding: 4px;
        margin: 2px 0;
        align-items: center;

        &.sortable-chosen {
            outline: 1px solid black;
        }

        & > .handle {
            & > i {
                display: block;
                height: 2px;
                width: 15px;
                margin: 2px;
                background: gray;
            }

            display: flex;
            flex-direction: column;
            padding: 0 4px;
            cursor: move;

            &:hover {
                background: whitesmoke;
            }
        }
    }

    .fa-chevron-down {
        &.r {
            transform: rotate(180deg);
        }

        transition: .2s;
    }

    .changed-badge {
        color: #026aca;
        font-size: 0.5em;
        margin-left: 0.5em;
        vertical-align: baseline;
    }
</style>
