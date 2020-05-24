<template>
    <sections>
        <admin-section :in-progress="submitting">
            <template v-slot:header>
                <ul class="breadcrumb">
                    <li>Courses</li>
                    <li>{{ name || 'new' }}</li>
                </ul>
            </template>

            <form class="form" @submit.prevent="onSubmit">
                <div class="form__control">
                    <label>
                        <input class="input" v-model="available" type="checkbox">
                        Available for purchase
                    </label>
                </div>

                <div class="form__control">
                    <select id="CategoryInput" v-model="categoryId" required>
                        <option :value="null" disabled>Chose a category</option>
                        <option :value="c.id" :key="c.id" v-for="c in categories">{{ c.name }}</option>
                    </select>
                </div>

                <div class="form__control">
                    <label for="Name" class="form__label">Name</label>
                    <input type="text" class="input" id="Name" v-model="name">
                </div>

                <div class="form__control">
                    <label for="Summary" class="form__label">Short summary</label>
                    <small>In a few words: what is this about?</small><br>
                    <textarea type="text" class="input" id="Summary" v-model="summary"></textarea>
                </div>

                <div class="form__control">
                    <label for="Price" class="form__label">Price</label>
                    <input v-currency type="text" class="input" id="Price" v-model="price" />
                </div>

                <div class="form__control">
                    <input
                        id="HasSignupPeriod"
                        type="checkbox"
                        class="input"
                        v-model="hasSignUpPeriod">

                    <label for="HasSignupPeriod">Has sign up period</label>
                    <div v-show="hasSignUpPeriod" class="d--flex">
                        <div class="form__control mr--2">
                            <label for="SignUpBeg" class="form__label">Starts at</label>
                            <input id="SignUpBeg" type="date" v-model="signUpBeg" class="input" :aria-invalid="signUpInvalid" />
                        </div>

                        <div class="form__control">
                            <label for="SignUpEnd" class="form__label">Ends at</label>
                            <input id="SignUpEnd" type="date" v-model="signUpEnd" class="input" :aria-invalid="signUpInvalid" />
                        </div>
                    </div>
                </div>

                <div class="form__control">
                    <label>Summary</label>
                    <markdown-editor v-model="about" />
                </div>

                <input :disabled="submitting" type="submit" class="btn btn--primary" value="Save">
            </form>
        </admin-section>
        <units-editor :course="course" @saved="onIdChanged" v-if="persistent" />
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

    @Component({
        components: {Sections, AdminSection, MarkdownEditor, UnitsEditor, Loader, Page}
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
        categoryId: number = null;
        price: string = null;
        notFound: boolean = false;

        hasSignUpPeriod = false;
        error = null;
        submitting = false;


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
                this.categoryId = c.category.id
                this.summary = ''
                this.about = c.about
                this.available = c.available
                this.signUpBeg = c.requirements.signUp.beg
                this.signUpEnd = c.requirements.signUp.end
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
                categoryId: this.categoryId
            }

            if (this.hasSignUpPeriod) {
                r.signUpBeg = this.signUpBeg
                r.signUpEnd = this.signUpEnd
            }

            this.submitting = true

            try {
                this.course = await this.admin.courses.create(r)
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
                await this.admin.courses.update({
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
        }

        mounted() {
            let $price = this.$refs.priceInput as HTMLInputElement;
        }
    }
</script>

<style scoped>

</style>
