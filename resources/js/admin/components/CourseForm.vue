<template>
    <page :title="courseData.name || 'New course'">
        <template v-slot:header v-if="persistent">
            <div class="d--flex">
                <router-link class="btn" :to="{name: 'course', params: {id: $route.params.id}}">View course</router-link>
                <router-link class="ml--1 btn" :to="{name: 'edit_course_units', params: {id: courseData.id}}">Edit units</router-link>
            </div>
        </template>

        <form class="form" @submit.prevent="onSubmit">
            <div class="form__control">
                <select id="CategoryInput">
                    <option selected>Chose a category</option>
                    <option :value="c.id" v-for="c in categories">{{ c.name }}</option>
                </select>
            </div>

            <div class="form__control">
                <label for="Name">Name</label>
                <input type="text" class="input" id="Name" v-model="courseData.name">
                <label for="Price">Price</label>
                <input type="text" class="input" id="Price" ref="priceInput" v-model="courseData.price">
            </div>

            <div class="form__control">
                <input
                    id="HasSignupPeriod"
                    type="checkbox"
                    class="input"
                    v-model="hasSignUpPeriod">
                <label for="HasSignupPeriod">Has sign up period</label>
                <div v-show="hasSignUpPeriod" class="d--flex fxw--wrap">
                    <div class="form__control mr--2">
                        <label class="form__label">Starts at</label>
                        <input ref="signUpBeg" v-model="courseData.signUpBeg" class="input" />
                    </div>

                    <div class="form__control">
                        <label class="form__label">Ends at</label>
                        <input ref="signUpEnd"  v-model="courseData.signUpEnd" class="input" />
                    </div>
                </div>
            </div>

            <div class="form__control">
                <label>Summary</label>
                <markdown-editor v-model="courseData.about" />
            </div>

            <input :disabled="submitting" type="submit" class="btn btn--primary" value="Save">
        </form>
    </page>
</template>

<script lang="ts">


    import {Component, dto, Prop, requests, Watch} from "@common";
    import {Page} from "@common/components/pages";
    import {Loader, MarkdownEditor} from "@common/components/utils";
    import UnitsEditor from "@admin/components/UnitsEditor.vue";
    import AdminStoreComponent from "@admin/components/AdminStoreComponent";

    @Component({
        components: {MarkdownEditor, UnitsEditor, Loader, Page}
    })
    export default class CourseForm extends AdminStoreComponent {
        persistent: boolean = false;
        courseData: requests.CreateCourse | requests.UpdateCourse = {};
        hasSignUpPeriod = false;
        errors = null;
        submitting = false;
        categories: dto.CategoryDto[] = [];


        @Prop({ type: Object }) course?: dto.CourseDto;

        async onSubmit() {
            if (!this.persistent)
                this.create();
            else
                this.update()
        }

        async create() {
            if (!this.persistent) {
                this.submitting = true;
                let course: dto.CourseDto;
                try
                {
                    course = await this.admin.courses.create(this.courseData as requests.CreateCourse);
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
                    await this.admin.courses.update({id: this.course.id, data: this.courseData})
                }
                catch (e) {
                    this.submitting = false;
                    this.errors = e.response.data;
                    return;
                }
                this.submitting = false;
            }
        }

        async init() {
            let categories = await this.store.courses.getCategories()
            this.categories = categories.categories
            if (this.course) {
                this.persistent = true;
                let r: requests.UpdateCourse = {
                    name: this.course.name,
                    signUpBeg: this.course.requirements.signUp.beg,
                    signUpEnd: this.course.requirements.signUp.end,
                    about: this.course.about,
                    price: this.course.price
                };
                this.courseData = r;
            }
            else
            {
                this.courseData = {};
            }
            this.hasSignUpPeriod = !!this.courseData.signUpEnd;
        }

        created() {
            this.init()
        }

        mounted() {
            let $price = this.$refs.priceInput as HTMLInputElement;

            IMask($price, {
                mask: Number,
                min: 0
            })
        }

        @Watch('course')
        courseChanged() {
            this.init()
        }
    }
</script>

<style scoped>

</style>
