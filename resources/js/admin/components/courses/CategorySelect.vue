<template>
    <div class="form-group">
        <select class="custom-select" :required="required"  v-model="category" @change="$emit('input', category)" v-if="categories.length">
            <option :value="null" v-if="defaultText">{{ defaultText }}</option>
            <option :value="cat" :key="cat.id" v-for="cat in categories">#{{ cat.id }} {{ cat.name }}</option>
        </select>
        <div class="pulse inline-block s1" v-else></div>
    </div>
</template>

<script lang="ts">
    import {Component, Prop, dto, Watch} from "@common";
    import AdminStoreComponent from "@admin/components/AdminStoreComponent";

    @Component({
        name: "CategorySelect"
    })
    export default class CategorySelect extends AdminStoreComponent {
        @Prop({default: false, type: Boolean}) required: boolean;
        @Prop({default: false, type: Boolean}) disabled: boolean;
        @Prop({default: 'Chose category', type: String}) defaultText: string;
        @Prop() value: dto.CategoryDto;

        categories: dto.CategoryDto[] = []
        category: dto.CategoryDto = null

        async load() {
            this.categories = (await this.store.courses.getCategories()).categories
        }

        created() {
            this.load()
            this.$emit('input', null)
        }

        @Watch('value')
        onValueChange() {
            this.category = this.value
        }
    }
</script>

<style scoped lang="scss">
    .pulse {
        width: 230px;
    }
</style>
