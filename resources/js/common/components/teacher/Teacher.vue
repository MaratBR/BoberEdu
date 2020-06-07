<template>
    <div class="teacher container">
        <div class="teacher__body">
            <header class="header">
                <h2 class="title">{{ name }}</h2>
            </header>

            <markdown-viewer :value="about" />
            <hr>
            <h3>Courses by {{ name }}</h3>

            <div class="courses">
                <course-wide-card v-for="course in courses" :course="course":key="course.id" />
            </div>
        </div>

        <aside class="teacher__about">
            <img class="img-thumbnail rounded-circle s210" :src="avatar" alt="">

            <div class="links">
                <social-link v-if="linkWeb" type="web" :href="linkWeb">Web-site</social-link>
                <social-link v-if="linkVk" type="web" :href="linkVk">VK</social-link>
                <social-link v-if="linkYt" type="yt" :href="linkYt">YouTube</social-link>
                <social-link v-if="linkLinkedIn" type="linkedIn" :href="linkLinkedIn">LinkedIn</social-link>
                <social-link v-if="linkFb" type="fb" :href="linkFb">Facebook</social-link>
                <social-link v-if="linkTwitter" type="twitter" :href="linkTwitter">Twitter</social-link>
            </div>
        </aside>
    </div>
</template>

<script lang="ts">
    import {Vue, Component, Prop, Watch} from "@common";
    import SocialLink from "@common/components/utils/SocialLink.vue";
    import {StoreComponent} from "@common/components/utils";
    import MarkdownViewer from "@common/components/utils/MarkdownViewer.vue";
    import CourseWideCard from "@common/components/courses/CourseWideCard.vue";

    @Component({
        name: "Teacher",
        components: {CourseWideCard, MarkdownViewer, SocialLink}
    })
    export default class Teacher extends StoreComponent {
        @Prop({required: true, type: Number}) id: number;

        avatar = null;
        name = null;
        about = null;
        inProgress = true
        courses = []

        linkVk = null
        linkYt = null
        linkFb = null
        linkLinkedIn = null
        linkWeb = null
        linkTwitter = null

        @Watch('id')
        async load() {
            let teacher = await this.store.getTeacher(this.id)
            this.name = teacher.fullName
            this.avatar = teacher.avatar
            this.about = teacher.about
            this.courses = teacher.courses
            this.inProgress = false
        }

        created() {
            this.load()
        }
    }
</script>

<style scoped lang="scss">
    .teacher {
        display: grid;
        grid-template-columns: 1fr auto;
        grid-gap: 10px;

        &__body {
            padding: 10px;
        }
    }
</style>
