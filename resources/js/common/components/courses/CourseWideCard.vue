<template>
    <router-link class="clear" :to="{name: 'course', params: {id: course.id}}">
        <div class="course-w">
            <div class="course-w__image" v-if="course.image">
                <div class="avatar s60">
                    <img :src="course.image" alt="">
                </div>
            </div>

            <div class="course-w__about">
                <header class="name">
                    {{ course.name }}
                    <small class="name__info">{{ course.info.lc }} lessons | {{ course.info.uc }} units</small>
                </header>
                <span class="summary">{{ course.summary || '(no summary provided)' }}</span>
            </div>

            <div class="course-w__about--right">
                <star-rating :rating="course.rating" :round-start-rating="false" :star-size="15" :read-only="true" />
                <span><i class="fa fa-usd"></i>{{ course.price }}</span>
            </div>
        </div>
    </router-link>
</template>

<script lang="ts">
    import {Vue, Component, Prop, dto} from "@common";

    @Component({
        name: "CourseWideCard"
    })
    export default class CourseWideCard extends Vue {
        @Prop({type: Object, required: true}) course: dto.CoursePageItemDto
    }
</script>

<style scoped lang="scss">
    .name {
        font-size: 1.2em;

        &__info {
            color: #555;
            font-size: 0.7em;
        }
    }

    .summary {
        font-size: 0.9em;
        color: #444;
    }

    a:active .course-w {
        background: #f2f2f2;
    }

    .course-w {
        margin: 7px 0;
        border: 1px solid #f5f5f5;
        padding: 15px;
        border-radius: 20px;
        background: white;
        transition: .3s;
        display: flex;

        &:hover {
            background: #f9f9f9;

            .name {
                text-decoration: underline !important;
            }
        }

        &__image {
            padding-right: 10px;
        }

        &__about {
            flex-grow: 1;

            &--right {

            }
        }
    }
</style>
