<template>
    <div class="container">
        <loader v-if="!categories" />
        <div v-else class="categories">
            <router-link v-for="cat in categories" class="cat"
                         :key="cat.id"
                         :to="{name: 'category', params: {id: cat.id}}">
                <div>
                    <div class="cat__name">{{cat.name}}</div>
                    <div class="cat__about">{{cat.about}}</div>
                </div>
            </router-link>
        </div>
    </div>
</template>

<script lang="ts">
    import {Loader, StoreComponent} from "@common/components/utils";
    import {Component, dto} from "@common";

    @Component({
        components: {Loader}
    })
    export default class Categories extends StoreComponent {
        categories: dto.CategoryDto[] = null;

        async created() {
            let categories = await this.store.courses.getCategories();
            this.categories = categories.categories;
            console.log(categories)
        }
    }
</script>

<style scoped lang="scss">
    .cat {
        min-height: 250px;
        min-width: 250px;
        border: 4px solid whitesmoke;
        margin: 8px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 30px;
        box-sizing: border-box;
        text-decoration: none;
        color: inherit;
        transition: 0.2s;

        &__name {
            font-size: 1.4em;
        }

        &:hover {
            border-color: #00a6f9;
            background: #00a6f9;
            color: white;

        }
    }

    .categories {
        display: flex;
        flex-wrap: wrap;
    }
</style>
