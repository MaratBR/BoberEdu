<template>
    <sections>
        <admin-section :in-progress="submitting">
            <template v-slot:header>
                <ul class="breadcrumb">
                    <li><router-link :to="{name:'admin__courses'}">Courses</router-link></li>
                    <li>{{ name || 'new' }}</li>
                </ul>
            </template>

            <error :error="error" v-if="error" />

            <form class="form" @submit.prevent="onSubmit">
                <div class="control" v-if="!persistent">
                    <category-select v-model="category" />
                </div>

                <div class="control">
                    <label>
                        <input class="input" v-model="available" type="checkbox">
                        Available for purchase
                    </label>
                </div>

                <input-text required label="Name" v-model="name" />
                <input-textarea required label="Summary" v-model="summary" />
                <input-text v-currency required label="Price" v-model="price" />

                <div class="control">
                    <label>
                        <input
                            type="checkbox"
                            class="input"
                            v-model="hasSignUpPeriod">
                        Has sign up period
                    </label>
                    <div v-show="hasSignUpPeriod">
                        <input-text label="Stars at" type="date" v-model="signUpBeg" required />
                        <input-text label="Ends at" type="date" v-model="signUpEnd" required />
                    </div>
                </div>

                <div class="control">
                    <label>Summary</label>
                    <markdown-editor v-model="about" />
                </div>

                <input :disabled="submitting" type="submit" class="btn btn--primary" value="Save">
                <p v-if="!persistent">
                    You can edit units and assign teachers after you save the course
                </p>
            </form>
        </admin-section>
        <units-editor :course="course" @saved="onIdChanged" v-if="persistent" />
        <course-teachers-form :course="course" @updated="onIdChanged" v-if="persistent" />
    </sections>
</template>

<script lang="ts">
    import {Component, dto, Prop, requests, Watch} from "@common";
    import {Page} from "@common/components/pages";
    import {Loader, MarkdownEditor} from "@common/components/utils";
    import UnitsEditor from "@admin/components/courses/UnitsEditor.vue";
    import AdminStoreComponent from "@admin/components/AdminStoreComponent";
    import AdminSection from "@admin/components/layout/AdminSection.vue";
    import Sections from "@admin/components/layout/Sections.vue";
    import {getError} from "@common/utils";
    import Error from "@common/components/utils/Error.vue";
    import CourseTeachersForm from "@admin/components/courses/CourseTeachersForm.vue";
    import CategorySelect from "@admin/components/courses/CategorySelect.vue";
    import InputText from "@common/components/forms/InputText.vue";
    import InputTextarea from "@common/components/forms/InputTextarea.vue";

    @Component({
        components: {
            InputTextarea,
            InputText,
            CategorySelect,
            CourseTeachersForm, Error, Sections, AdminSection, MarkdownEditor, UnitsEditor, Loader, Page}
    })
    export default class CourseForm extends AdminStoreComponent {
        @Prop({default: null}) id: number;

        categories: dto.CategoryDto[] = [];
        course: dto.CourseExDto = null;

        get persistent() {
            return this.course !== null
        }

        name: string = null;
        summary: string = null;
        available: boolean = null;
        about: string = null;
        signUpBeg: string = null;
        signUpEnd: string = null;
        signUpInvalid: boolean = false;
        category: dto.CategoryDto = null;
        price: string = null;
        notFound: boolean = false;

        hasSignUpPeriod = false;
        error = null;
        submitting = true;


        set priceAsNumber(v: number) {
            this.price = v+''
        }

        get priceAsNumber(): number {
            return +(this.price.charAt(0) === '$' ? this.price.substr(1) : this.price).replace(',', '')
        }

        @Watch('signUpBeg')
        @Watch('signUpEnd')
        validateSignUp() {
            this.signUpInvalid = +new Date(this.signUpEnd) <= +new Date(this.signUpBeg)
        }

        @Watch('id')
        async onIdChanged() {
            this.submitting = true

            if (this.id === null) {
                this.name = this.summary = this.about = this.signUpBeg = this.signUpEnd = this.course = null
                this.available = false
                this.submitting = false
                return
            }

            try {
                let c = await this.store.courses.get(this.id)
                this.name = c.name
                this.category = c.category
                this.summary = ''
                this.about = c.about
                this.available = c.available
                this.signUpBeg = c.requirements.signUp.beg
                this.signUpEnd = c.requirements.signUp.end
                this.summary = c.summary
                this.priceAsNumber = c.price
                this.course = c
            } catch (e) {
                this.notFound = true
            } finally {
                this.submitting = false
            }
        }

        onSubmit() {
            if (!this.persistent)
                this.create();
            else
                this.update()
        }

        async create() {
            if (this.persistent)
                return

            let r: requests.CreateCourse = {
                name: this.name,
                price: this.priceAsNumber,
                summary: this.summary,
                about: this.about,
                available: this.available,
                categoryId: this.category.id
            }

            if (this.hasSignUpPeriod) {
                r.signUpBeg = this.signUpBeg
                r.signUpEnd = this.signUpEnd
            }

            this.submitting = true

            try {
                this.course = await this.admin.createCourse(r)
                await this.$router.replace({
                    name: 'admin__courses_edit',
                    params: {
                        id: this.course.id+''
                    }
                })
            } catch (e) {
                this.error = getError(e)
            } finally {
                this.submitting = false
            }
        }

        async update() {
            if (!this.persistent)
                return

            let r: requests.UpdateCourse = {
                name: this.name,
                price: this.priceAsNumber,
                summary: this.summary,
                about: this.about,
                available: this.available,
            }

            if (this.hasSignUpPeriod) {
                r.signUpBeg = this.signUpBeg
                r.signUpEnd = this.signUpEnd
            }

            this.submitting = true

            try {
                await this.admin.updateCourse({
                    id: this.id,
                    data: r
                })
                await this.onIdChanged()
            } catch (e) {
                this.error = getError(e)
            } finally {
                this.submitting = false
            }
        }

        async init() {
            let categories = await this.store.courses.getCategories()
            this.categories = categories.categories
        }


        async created() {
            await this.init()
            if (this.id !== null)
                await this.onIdChanged()
            else
                this.submitting = false
        }

        mounted() {
            let $price = this.$refs.priceInput as HTMLInputElement;
        }
    }
</script>

<style scoped>

</style>
