<template>
    <sections>
        <admin-section header="Categories" :in-progress="loading">
            <error v-if="error" :error="error" />
            <form @submit.prevent="onSubmit" class="categories-list form">

                <label>
                    Select category
                    <select v-model="category">
                        <option :value="null" disabled>Chose name from the list</option>
                        <option :value="c" :key="c.id" v-for="c in categories">{{ c.name }}</option>
                    </select>
                </label><br>
                <button @click.prevent="addNew"><i class="fa fa-plus"></i> add new</button>


                <template v-if="category || isNew">
                    <hr>
                    <h3 v-if="category">Edit: {{ name }}</h3>
                    <h3 v-if="isNew">New category</h3>
                    <div class="control">
                        <label>
                            Name
                            <input required type="text" v-model="name" class="input" :disabled="!category && !isNew">
                        </label>
                    </div>


                    <div class="control">
                        <label>
                            About
                            <textarea type="text" v-model="about" class="input" :disabled="!category && !isNew" />
                        </label>
                    </div>

                    <div class="control">
                        <label>
                            Color<br>
                            <input required type="color" v-model="color" class="input" :disabled="!category && !isNew">
                        </label>
                    </div>

                    <div class="control" v-if="!isNew && category">
                        <label>Background image</label><br>
                        <uploader v-model="bgImage" @upload="uploadImage" :uploading="uploading" accept="image/*" />
                    </div>

                    <input type="submit" value="Save">
                </template>
            </form>
        </admin-section>
    </sections>
</template>

<script lang="ts">
    import {Vue, Component, dto, Watch} from "@common";
    import Sections from "@admin/components/layout/Sections.vue";
    import AdminSection from "@admin/components/layout/AdminSection.vue";
    import Loader from "@common/components/utils/Loader.vue";
    import Error from "@common/components/utils/Error.vue";
    import AdminStoreComponent from "@admin/components/AdminStoreComponent";
    import Uploader from "@common/components/utils/Uploader.vue";
    import {getError} from "@common/utils";

    @Component({
        name: "Categories",
        components: {Uploader, Error, Loader, AdminSection, Sections}
    })
    export default class Categories extends AdminStoreComponent {
        loading = true;
        categories: dto.CategoryDto[] = null
        error = null;
        category: dto.CategoryDto = null;
        name = null;
        about = null;
        color = null;
        bgImageId = null;
        uploading = false;
        bgImage: File = null;
        isNew = false;

        @Watch('category')
        onCategoryChanged() {
            if (this.category) {
                this.name = this.category.name
                this.about = this.category.about
                this.color = '#' + this.category.color
                this.bgImageId = this.category.bgImage
            } else {
                this.reset()
            }
        }

        async init() {
            try {
                this.categories = (await this.store.courses.getCategories()).categories
            } finally {
                this.loading = false
            }
        }

        created() {
            this.init()
        }

        addNew() {
            this.isNew = true
            this.category = null
            this.reset()
        }

        reset() {
            this.name = this.about = this.bgImageId = this.bgImage = null
            this.color = '#000000'
        }

        async onSubmit() {
            this.loading = true
            try {
                if (this.isNew) {
                    this.category = await this.admin.createCategory({
                        name: this.name,
                        about: this.name,
                        color: this.color.substr(1)
                    })
                } else {
                    await this.admin.updateCategory({
                        id: this.category.id,
                        data: {
                            name: this.name,
                            about: this.about,
                            color: this.color.substr(1)
                        }
                    })
                }

                await this.init()
                if (this.isNew)
                    this.isNew = false
                else
                    this.category = this.categories.find(c => c.id == this.category.id)
            } catch (e) {
                this.error = getError(e)
            } finally {
                this.loading = false
            }
        }

        uploadImage() {
            this.admin.uploadCategoryImage({
                id: this.category.id,
                data: this.bgImage
            })
        }
    }
</script>

<style scoped lang="scss">

</style>
