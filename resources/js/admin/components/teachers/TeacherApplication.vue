<template>
    <admin-section>
        <template v-slot:header>
            <ul class="breadcrumb breadcrumb-clear">
                <li class="breadcrumb-item">
                    <router-link :to="{name: 'admin__teachers_applications'}">
                        Teacher applications
                    </router-link>
                </li>
                <li class="breadcrumb-item">
                    {{ application.user.name }}
                </li>
            </ul>
        </template>

        <dl>

            <dt>Application send by</dt>
            <dd>{{ application.user.name }}#{{ application.user.id }}</dd>

            <dt>Full name</dt>
            <dd>{{application.fullName}}</dd>

            <dt>Location</dt>
            <dd>{{application.fullName}}</dd>

            <dt>Education</dt>
            <dd>{{application.education}}</dd>

            <dt>Degree</dt>
            <dd>{{application.degree}}</dd>

            <dt>Extra info</dt>
            <dd>{{application.extra}}</dd>


            <div class="form-group" v-if="application.approved === null">
                <button class="btn btn-danger" @click="reject">Reject</button>
                <button class="btn btn-outline-success" @click="approve">Accept</button>
            </div>
        </dl>
    </admin-section>
</template>

<script lang="ts">
    import {Vue, Component, dto, Prop} from "@common";
    import AdminSection from "@admin/components/layout/AdminSection.vue";
    import AdminStoreComponent from "@admin/components/AdminStoreComponent";

    @Component({
        name: "TeacherApplication",
        components: {AdminSection}
    })
    export default class TeacherApplication extends AdminStoreComponent {
        application: dto.TeacherApplicationExDto = null
        @Prop({ required: true }) id: number;
        notFound = false;
        inProgress = false;

        async load() {
            this.inProgress = true
            try {
                this.application = await this.admin.getTeacherApplication(this.id)
            } catch (e) {
                this.notFound = true
            } finally {
                this.inProgress = false
            }
        }

        async reject() {
            this.inProgress = true
            await this.admin.rejectTeacherApplication(this.id)
            await this.load()
        }

        async approve() {
            this.inProgress = true
            await this.admin.approveTeacherApplication(this.id)
            await this.load()
        }

        created() {
            this.load()
        }
    }
</script>

<style scoped lang="scss">

</style>
