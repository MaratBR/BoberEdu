<template>
    <page :title="courseData.name || 'New course'">
        <template v-slot:header>
            <div class="d--flex">
                <router-link class="btn" :to="{name: 'course', params: {id: courseData.id}}">View course</router-link>
                <router-link class="ml--1 btn" v-if="persistent" :to="{name: 'edit_course_units', params: {id: courseData.id}}">Edit units</router-link>
            </div>
        </template>

        <form class="form" @submit.prevent="onSubmit">
            <validation-provider v-slot="{errors}" rules="required">
            <div class="form__control">
                <label for="Name">Name</label>
                <input type="text" class="input" :aria-invalid="errors.length !== 0" id="Name" v-model="courseData.name">
                <span class="input__error">{{errors[0]}}</span>
            </div>
            </validation-provider>

            <validation-provider v-slot="{errors}" rules="required|min_value:0">
                <div class="form__control">
                    <label for="Price">Price</label>
                    <input type="text" class="input" :aria-invalid="errors.length !== 0" id="Price" v-model="courseData.price" />
                    <span v-for="err in errors" class="input__error">{{err}}</span>
                </div>
            </validation-provider>

            <div>
                <input
                    id="HasSignupPeriod"
                    type="checkbox"
                    class="input"
                    v-model="hasSignUpPeriod">
                <label for="HasSignupPeriod">Has sign up period</label>
                <div v-show="hasSignUpPeriod" class="d--flex fxw--wrap">
                    <div class="form__control mr--2">
                        <label class="form__label">Starts at</label>
                        <date-input @input="$forceUpdate()" v-model="courseData.sign_up_beg" class="input" output-format="YYYY-MM-DD" />
                    </div>

                    <div class="form__control">
                        <label class="form__label">Ends at</label>
                        <date-input @input="$forceUpdate()" v-model="courseData.sign_up_end" output-format="YYYY-MM-DD" class="input" />
                    </div>
                </div>
            </div>

            <validation-provider v-slot="{errors}" rules="required">
                <div class="form__control">
                    <label>Summary</label>
                    <markdown-editor  v-model="courseData.about" />
                </div>
            </validation-provider>

            <error :error="errors" />

            <input :disabled="submitting" type="submit" class="btn btn--primary" value="Save">
        </form>
    </page>
</template>

<script lang="ts">
    import Page from "./Page.vue";
    import Loader from "../misc/Loader.vue";
    import Error from "../misc/Error.vue";
    import UnitsEditor from "./UnitsEditor.vue";
    import DateInput from "../misc/DateInput.vue";
    import MarkdownEditor from "../misc/MarkdownEditor.vue";
    import {Component, Emit, Prop, Vue, Watch} from "vue-property-decorator";
    import {Course, CreateCourseDate, UpdateCourseData} from "../../store/modules/CoursesModule";
    import {useStore} from "vuex-simple";
    import {Store} from "../../store";
    import {getStagedChangeset, makeStagedProxy} from "../../models";

    @Component({
        components: {MarkdownEditor, DateInput, UnitsEditor, Error, Loader, Page}
    })
    export default class CourseForm extends Vue {
        persistent: boolean = false;
        courseData: CreateCourseDate | UpdateCourseData = {};
        hasSignUpPeriod = false;
        errors = null;
        submitting = false;

        store: Store = useStore(this.$store);

        @Prop({ type: Object }) course?: Course;

        async onSubmit() {
            if (!this.persistent)
                this.create();
            else
                this.update()
        }

        async create() {
            if (!this.persistent) {
                this.submitting = true;
                let course: Course;
                try
                {
                    course = await this.store.courses.create(getStagedChangeset(this.courseData) as CreateCourseDate);
                }
                catch (e)
                {
                    this.submitting = false;
                    this.errors = e.response.data;
                    return;
                }
                this.$emit('created', course);
                this.submitting = false;
            }
        }

        async update() {
            if (this.persistent) {
                this.submitting = true;
                try
                {
                    let data = getStagedChangeset(this.courseData);
                    await this.store.courses.update({id: this.course.id, data})
                }
                catch (e) {
                    this.submitting = false;
                    this.errors = e.response.data;
                    return;
                }
                this.submitting = false;
            }
        }

        init() {
            if (this.course)
            {
                this.persistent = true;
                this.courseData = makeStagedProxy({
                    name: this.course.name,
                    sign_up_beg: this.course.sign_up_beg,
                    sign_up_end: this.course.sign_up_end,
                    about: this.course.sign_up_end,
                    price: this.course.price
                } as UpdateCourseData);
            }
            else
            {
                this.courseData = makeStagedProxy({})
            }
            this.hasSignUpPeriod = !!this.courseData.sign_up_beg;
        }

        created() {
            this.init()
        }

        @Watch('course')
        courseChanged() {
            this.init()
        }
    }
</script>

<style scoped>

</style>
