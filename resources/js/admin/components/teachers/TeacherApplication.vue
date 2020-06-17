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

        <not-found v-if="state === 2">Application not found</not-found>
        <loader v-else-if="state === 1" />
        <dl v-else>

            <dt>Application send by</dt>
            <dd>{{ application.user.name }}#{{ application.user.id }}</dd>

            <dt>Full name</dt>
            <dd>{{application.fullName}}</dd>

            <dt>Location</dt>
            <dd>{{application.location}}</dd>

            <dt>Education</dt>
            <dd>{{application.education}}</dd>

            <dt>Degree</dt>
            <dd>{{application.degree}}</dd>

            <dt>Extra info</dt>
            <dd>{{application.extra}}</dd>


            <div class="form-group" v-if="application.approved === null">
                <save-button inline type="danger" saved-text="Done" text="Reject" saving-text=""
                             :saving="state === 3" :disabled="state === 4" @click="reject" />
                <save-button inline type="secondary" saved-text="Done" text="Accept" saving-text=""
                             :saving="state === 4" :disabled="state === 3" @click="approve" />
            </div>
            <span class="font-weight-bold" v-else>
                Application was {{ application.approved ? 'approved' : 'rejected' }} by
                <router-link :to="{name: 'profile', params: {id: application.approvedBy.id}}">
                    {{ application.approvedBy.name }}
                </router-link>
            </span>
        </dl>
    </admin-section>
</template>

<script lang="ts">
    import {Vue, Component, dto, Prop} from "@common";
    import AdminSection from "@admin/components/layout/AdminSection.vue";
    import AdminStoreComponent from "@admin/components/AdminStoreComponent";
    import SaveButton from "@common/components/forms/SaveButton.vue";
    import Loader from "@common/components/utils/Loader.vue";
    import NotFound from "@common/components/pages/NotFound.vue";

    @Component({
        name: "TeacherApplication",
        components: {NotFound, Loader, SaveButton, AdminSection}
    })
    export default class TeacherApplication extends AdminStoreComponent {
        application: dto.TeacherApplicationExDto = null
        @Prop({ required: true }) id: number;
        state = 1;


        async load() {
            this.state = 1
            try {
                this.application = await this.admin.getTeacherApplication(this.id)
            } catch (e) {
                this.state = 2
            } finally {
                this.state = 0
            }
        }

        async reject() {
            this.state = 3
            await this.admin.rejectTeacherApplication(this.id)
            await this.load()
            this.state = 0
        }

        async approve() {
            debugger
            this.state = 4
            await this.admin.approveTeacherApplication(this.id)
            await this.load()
            this.state = 0
        }

        created() {
            this.load()
        }
    }
</script>

<style scoped lang="scss">

</style>
