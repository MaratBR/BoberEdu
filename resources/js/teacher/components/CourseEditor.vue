<template>
    <not-found v-if="notFound">
        <p>Course you requested does not exist or you can't have access to it</p>
    </not-found>
    <loader v-else-if="inProgressState === 1" />
    <div class="container pt-4" v-else>
        <form action="#" @submit.prevent="submit">
            <category-select v-if="isNew" v-model="category" required />

            <div class="form-group">
                <img :src="image" class="img-thumbnail rounded-circle s180">
            </div>

            <div class="form-group">
                <uploader :disabled="inProgressState" :uploading="uploading" v-model="imageFile" accept="image/*" default-text="Upload image" @upload="uploadImage()" />
                <small v-if="showUploadHint">Image will be uploaded on save</small>
            </div>

            <input-text label="Name" required v-model="name" :disabled="inProgressState === 2" />
            <input-textarea label="Summary" required v-model="summary" :disabled="inProgressState === 2" />
            <input-text v-currency label="Price" v-model="price" :disabled="inProgressState === 2" />
            <markdown-editor v-model="about" />

            <div class="form-check">
                <input
                    type="checkbox"
                    class="form-check-input"
                    v-model="hasSignUpPeriod">
                <label class="form-check-label">
                    Has sign up period
                </label>
            </div>

            <div v-if="hasSignUpPeriod">
                <input-text label="Stars at" type="date" v-model="signUpBeg" required :disabled="inProgressState === 2" />
                <input-text label="Ends at" type="date" v-model="signUpEnd" required :disabled="inProgressState === 2" />
            </div>

            <error :error="error" v-if="error" />

            <save-button :saving="inProgressState === 2" class="mt-1" />
        </form>


        <form action="#" v-if="!isNew" @submit.prevent="saveUnits">
            <hr>
            <h4>Units and lesson order</h4>
            <div class="units">
                <draggable v-model="units" handle=".handle" @end="onUnitDragEnd">
                    <div class="unit" v-for="u in units" :key="u.id" :class="{changed: u.changed, hidden: u.hidden}">
                        <div class="handle">
                            <i></i><i></i><i></i><i></i><i></i>
                        </div>

                        <div class="unit__name input-group">
                            <span class="input-group-prepend">
                                <div class="unit__preview input-group-text">
                                    <label class="mb-0">
                                        Preview
                                        <input type="checkbox" v-model="u.preview" @input="updateChanged(u)"
                                               :disabled="inProgressState === 3" />
                                    </label>
                                </div>
                            </span>

                            <input class=" form-control" type="text" v-model="u.name" @input="updateChanged(u)" :disabled="inProgressState === 3">
                        </div>
                        <textarea class="unit__about form-control" type="text" v-model="u.about" @input="updateChanged(u)" :disabled="inProgressState === 3" />

                        <div class="unit__buttons d-flex flex-column justify-content-center">
                            <button class="btn" @click.prevent="u.deleted = u.changed = true" :disabled="inProgressState === 3">
                                <i class="fas fa-times text-danger"></i>
                            </button>

                            <button class="btn" @click.prevent="u.hidden = !u.hidden" :disabled="inProgressState === 3">
                                <i class="fas fa-chevron-up"></i>
                            </button>
                        </div>


                        <div class="unit__lessons">
                            <ul>
                                <draggable v-model="u.lessons" @end="onLessonDragEnd(u, $event)">
                                    <li class="lesson" v-for="l in u.lessons" :key="l.id"  :class="{changed: l.changed}">
                                        <router-link :to="{name: 'teacher_dashboard__lesson_edit', params: {id: l.id}}">
                                            {{ l.name }}
                                            <small>(ID: {{l.id}})</small>
                                        </router-link>
                                    </li>
                                </draggable>
                            </ul>
                        </div>
                    </div>
                </draggable>

                <button class="btn" @click.prevent="addNew" :disabled="inProgressState === 3">
                    <i class="fas fa-plus"></i>
                    add new
                </button>
            </div>

            <save-button :saving="inProgressState === 3" class="mt-1" />
        </form>
    </div>
</template>

<script lang="ts">
    import {Vue, Component, Prop, dto, requests, Watch} from "@common";
    import TeachersStoreComponent from "@teacher/components/TeachersStoreComponent";
    import InputText from "@common/components/forms/InputText.vue";
    import InputTextarea from "@common/components/forms/InputTextarea.vue";
    import Loader from "@common/components/utils/Loader.vue";
    import format from 'date-fns/format'
    import {getError} from "@common/utils";
    import Uploader from "@common/components/utils/Uploader.vue";
    import Error from "@common/components/utils/Error.vue";
    import draggable from 'vuedraggable'
    import CategorySelect from "@common/components/courses/CategorySelect.vue";
    import SaveButton from "@common/components/forms/SaveButton.vue";
    import NotFound from "@common/components/pages/NotFound.vue";

    type LessonData = {
        id: number,
        name: string,
        changed: boolean
    }

    type UnitData = {
        lessons: LessonData[],
        id: number,
        name: string,
        preview: boolean,
        deleted: boolean,
        new: boolean,
        about: string,
        changed: boolean,
        hidden: boolean
    }

    @Component({
        name: "CourseEditor",
        components: {NotFound, SaveButton, CategorySelect, Error, Uploader, Loader, InputTextarea, InputText, draggable}
    })
    export default class CourseEditor extends TeachersStoreComponent {
        @Prop() id: number

        course: dto.CourseExDto = null

        isNew = false;
        notFound = false;
        inProgressState = 1
        hasSignUpPeriod = false
        uploading = false;
        imageFile: File = null;
        image: string = null;
        name = null;
        summary = null;
        about = '';
        price: string;
        available = null;
        signUpBeg: string = null
        trialDays = 0
        signUpEnd: string = null
        category: dto.CategoryDto = null
        error = null
        showUploadHint = false

        units: UnitData[] = []

        set priceAsNumber(v: number) {
            this.price = v+''
        }

        get priceAsNumber(): number {
            return +(this.price.charAt(0) === '$' ? this.price.substr(1) : this.price).replace(',', '')
        }

        async load() {
            try {
                this.updateFrom(await this.teacher.getCourse(this.id))
            } catch (e) {
                this.error = getError(e)
                this.notFound = true
            }
        }

        updateFrom(course: dto.CourseExDto) {
            this.course = course
            this.name = course.name
            this.summary = course.summary
            this.about = course.about
            this.priceAsNumber = course.price
            this.image = course.image
            this.available = course.available
            this.hasSignUpPeriod = !!course.requirements.signUp.beg
            this.signUpBeg = this.hasSignUpPeriod ? format(new Date(course.requirements.signUp.beg), 'yyyy-MM-dd') : ''
            this.signUpEnd = this.hasSignUpPeriod ? format(new Date(course.requirements.signUp.end), 'yyyy-MM-dd') : ''
            this.units = course.units.map(u => ({
                id: u.id,
                name: u.name,
                lessons: u.lessons.map(l => ({
                    id: l.id,
                    name: l.title,
                    changed: false
                })),
                about: u.about,
                preview: u.preview,
                new: false,
                deleted: false,
                changed: false,
                hidden: true
            }))
        }

        @Watch('id')
        async init() {
            this.inProgressState = 1
            this.isNew = typeof this.id !== 'number' || isNaN(this.id)

            if (this.isNew) {
                this.about = ''
                this.name = this.summary = this.image = null
                this.notFound = this.hasSignUpPeriod = this.available = false
                this.priceAsNumber = 0
                this.signUpBeg = this.signUpEnd = ''
            } else {
                await this.load()
            }

            this.inProgressState = 0
        }

        addNew() {
            this.units.push({
                name: '',
                about: '',
                new: true,
                deleted: true,
                hidden: true,
                lessons: [],
                preview: false,
                changed: true,
                id: null
            })
        }

        async submit() {
            this.error = null
            this.inProgressState = 2

            if (this.isNew) {
                this.isNew = false

                let course: dto.CourseExDto
                try {
                    course = await this.teacher.createCourse({
                        name: this.name,
                        available: this.available,
                        about: this.about,
                        summary: this.summary,
                        price: this.priceAsNumber,
                        signUpEnd: this.hasSignUpPeriod ? format(new Date(this.signUpEnd), 'yyyy-MM-dd') : null,
                        signUpBeg: this.hasSignUpPeriod ? format(new Date(this.signUpBeg), 'yyyy-MM-dd') : null,
                        trialLength: this.trialDays,
                        categoryId: this.category.id
                    })
                    this.updateFrom(course)
                } catch (e) {
                    this.inProgressState = 0
                    this.error = getError(e)
                    return
                }

                if (this.imageFile !== null) {
                    try {
                        await this.uploadImage(course.id)
                    } catch (e) {
                        this.error = getError(e)
                    }
                }

                await this.$router.replace({
                    name: 'teacher_dashboard__edit',
                    params: {
                        id: course.id+''
                    }
                })
            } else {
                let course = await this.teacher.updateCourse({
                    id: this.id,
                    data: {
                        name: this.name,
                        available: this.available,
                        about: this.about,
                        summary: this.summary,
                        price: this.priceAsNumber,
                        signUpEnd: this.hasSignUpPeriod ? format(new Date(this.signUpEnd), 'yyyy-MM-dd') : null,
                        signUpBeg: this.hasSignUpPeriod ? format(new Date(this.signUpBeg), 'yyyy-MM-dd') : null,
                        trialLength: this.trialDays
                    }
                })
                this.updateFrom(course)
            }

            this.inProgressState = 0
        }

        created() {
            this.init()
        }

        private uploadImage(id?: number) {
            if (this.imageFile === null)
                return Promise.resolve()

            if (this.isNew) {
                this.showUploadHint = true
                return
            }

            id = id || this.id
            this.uploading = true
            return this.teacher.uploadCourseImage({
                id, data: this.imageFile
            }).then(s => {
                this.uploading = false
                this.image = s
            })
        }

        onUnitDragEnd(e) {
            let i1 = e.newIndex, i2 = e.oldIndex;
            if (i1 > i2)
                [i1, i2] = [i2, i1];
            for (let i = i1; i <= i2; i++) {
                this.updateChanged(this.units[i])
            }
        }

        onLessonDragEnd(unit: UnitData, e) {
            let i1 = e.newIndex, i2 = e.oldIndex;
            if (i1 > i2)
                [i1, i2] = [i2, i1];
            let originalUnit = this.course.units.find(u => u.id == unit.id)
            for (let i = i1; i <= i2; i++) {
                let originalIndex = originalUnit.lessons.findIndex(l => l.id == unit.lessons[i].id)
                unit.lessons[i].changed = originalIndex != i
            }
        }

        updateChanged(unit: UnitData) {
            if (unit.new || unit.deleted) {
                unit.changed = true
                return
            }

            let originalIndex = this.course.units.findIndex(u => u.id == unit.id)
            let original = this.course.units[originalIndex]

            unit.changed = originalIndex !== this.units.indexOf(unit) || original.name !== unit.name || original.preview !== unit.preview
                || original.about !== unit.about
        }

        async saveUnits() {
            this.inProgressState = 3
            let r: requests.UpdateCourseUnits = {
                upd: this.units.filter(u => !u.deleted && !u.new && u.changed).map(u => ({
                    id: u.id,
                    name: u.name,
                    about: u.about,
                    preview: u.preview
                })),
                delete: this.units.filter(u => u.deleted && !u.new).map(u => u.id),
                new: this.units.filter(u => u.new).map(u => ({name: u.name, about: u.about, preview: u.preview})),
                order: (() => {
                    let i = 0;
                    return this.units.map(u => u.new ? ('n' + i++) : u.id+'')
                })()
            }

            let lessonsRequest: requests.UpdateLessonsOrder = {
                units: this.units.filter(u => {
                    return u.lessons.some(l => l.changed)
                }).map(u => ({
                    id: u.id,
                    order: u.lessons.map(l => l.id)
                }))
            }

            if (lessonsRequest.units.length)
                await this.teacher.updateLessonsOrder({
                    id: this.id, data: lessonsRequest
                })

            await this.teacher.updateCourseUnits({
                id: this.id, data: r
            })

            this.inProgressState = 0
        }
    }
</script>

<style scoped lang="scss">
    .unit {
        grid-template: auto 1fr auto / 40px 1fr auto;
        grid-gap: 3px;
        display: grid;
        padding: 10px 0;
        border-bottom: 1px solid #aaa;
        border-left: 4px solid transparent;

        &.sortable-chosen {
            background: #efefef;
        }


        &.hidden &__lessons {
            max-height: 0;
        }

        &.hidden .fa-chevron-up {
            transform: rotate(180deg);
        }

        &.changed {
            border-left-color: #026aca;
        }

        &__buttons {
            grid-area: 1 / 3 / 3 / 4;
        }

        &__lessons {
            grid-area: 3 / 2 / 4 / 4;
            margin: 0;
            max-height: 1000px;
            overflow: hidden;
            transition: .2s;
        }

        & > .handle {
            width: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            & > i {
                margin: 4px;
                display: block;
                height: 2px;
                background: gray;
                width: 30px;
            }
            grid-area: 1 / 1 / 3 / 2;
        }
    }

    .lesson {
        display: block;
        padding: 10px;
        border-bottom: 1px solid #eee;
        border-left: 4px solid transparent;
        background: white;
        margin: 5px;

        &.changed {
            border-left-color: #026aca;
        }

        &:hover {
            border-bottom-color: #ddd;
        }

        &.sortable-chosen {
            background: #efefef;
        }
    }
</style>
