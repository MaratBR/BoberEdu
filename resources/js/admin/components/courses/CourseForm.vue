<template>
    <sections>
        <admin-section :in-progress="submitting">
            <template v-slot:header>
                <ul class="breadcrumb breadcrumb-clear breadcrumb">
                    <li class="breadcrumb-item"><router-link :to="{name:'admin__courses'}">Courses</router-link></li>
                    <li class="breadcrumb-item active">{{ name || 'new' }}</li>
                </ul>
            </template>

            <error :error="error" v-if="error" />

            <form class="form" @submit.prevent="onSubmit">
                <div class="form-group" v-if="!persistent">
                    <category-select v-model="category" />
                </div>

                <div class="form-group">
                    <img :src="image" class="img-thumbnail rounded-circle s180">
                </div>

                <div class="form-group">
                    <uploader :uploading="uploading" v-model="imageFile" accept="image/*" default-text="Upload image" @upload="uploadImage()" />
                    <small v-if="showUploadHint">Image will be uploaded on save</small>
                </div>

                <div class="form-check">
                    <input class="form-check-input" v-model="available" type="checkbox">
                    <label class="form-check-label">Available</label>
                </div>

                <input-text required label="Name" v-model="name" />
                <input-textarea required label="Summary" v-model="summary" />
                <input-text type="number" label="Free trial days" v-model="trialDays" />
                <input-text v-currency required label="Price" v-model="price" />

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
                    <input-text label="Stars at" type="date" v-model="signUpBeg" required />
                    <input-text label="Ends at" type="date" v-model="signUpEnd" required />
                </div>

                <div class="form-group">
                    <label>Summary</label>
                    <markdown-editor v-model="about" />
                </div>

                <input :disabled="submitting" type="submit" class="btn btn-primary" value="Save">
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
    import {Loader} from "@common/components/utils";
    import UnitsEditor from "@admin/components/courses/UnitsEditor.vue";
    import AdminStoreComponent from "@admin/components/AdminStoreComponent";
    import AdminSection from "@admin/components/layout/AdminSection.vue";
    import Sections from "@admin/components/layout/Sections.vue";
    import {getError} from "@common/utils";
    import Error from "@common/components/utils/Error.vue";
    import CourseTeachersForm from "@admin/components/courses/CourseTeachersForm.vue";
    import CategorySelect from "@common/components/courses/CategorySelect.vue";
    import InputText from "@common/components/forms/InputText.vue";
    import InputTextarea from "@common/components/forms/InputTextarea.vue";
    import Uploader from "@common/components/utils/Uploader.vue";
    import format from 'date-fns/format'

    @Component({
        components: {
            Uploader,
            InputTextarea,
            InputText,
            CategorySelect,
            CourseTeachersForm, Error, Sections, AdminSection, UnitsEditor, Loader, Page}
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
        trialDays: number = 0;
        about: string = '';
        signUpBeg: string = null;
        signUpEnd: string = null;
        signUpInvalid: boolean = false;
        category: dto.CategoryDto = null;
        price: string = null;
        image: string = null
        imageFile: File = null
        notFound: boolean = false;
        showUploadHint: boolean = false
        uploading = false

        hasSignUpPeriod = false;
        error = null;
        submitting = true;


        set priceAsNumber(v: number) {
            this.price = v+''
        }

        get priceAsNumber(): number {
            return +(this.price.charAt(0) === '$' ? this.price.substr(1) : this.price).replace(',', '')
        }

        async uploadImage(id?: number) {
            if (typeof id === 'undefined') {
                if (this.persistent) {
                    id = this.id
                } else {
                    this.showUploadHint = true
                    return
                }
            }

            this.uploading = true

            this.image = await this.admin.uploadCourseImage({
                id, data: this.imageFile
            })

            this.uploading = false
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
                this.name = this.summary = this.about = this.signUpBeg = this.signUpEnd = this.course = this.image
                    = this.imageFile = null
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
                this.trialDays = c.trialDays
                this.hasSignUpPeriod = !!c.requirements.signUp.beg
                this.signUpBeg = this.hasSignUpPeriod ? format(new Date(c.requirements.signUp.beg), 'yyyy-MM-dd') : ''
                this.signUpEnd = this.hasSignUpPeriod ? format(new Date(c.requirements.signUp.end), 'yyyy-MM-dd') : ''
                this.summary = c.summary
                this.image = c.image
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
                categoryId: this.category.id,
                signUpEnd: this.hasSignUpPeriod ? format(new Date(this.signUpEnd), 'yyyy-MM-dd') : null,
                signUpBeg: this.hasSignUpPeriod ? format(new Date(this.signUpBeg), 'yyyy-MM-dd') : null,
                trialLength: this.trialDays
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
                signUpEnd: this.hasSignUpPeriod ? format(new Date(this.signUpEnd), 'yyyy-MM-dd') : null,
                signUpBeg: this.hasSignUpPeriod ? format(new Date(this.signUpBeg), 'yyyy-MM-dd') : null,
                trialLength: this.trialDays
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
