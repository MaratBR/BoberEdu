<template>
    <admin-section :in-progress="inProgress">
        <template v-slot:header v-if="!notFound">
            <ul class="breadcrumb breadcrumb-clear">
                <li class="breadcrumb-item"><router-link :to="{name: 'admin__teachers'}">Teachers</router-link></li>
                <li class="breadcrumb-item active">{{ isNew ? 'new' : fullName }} <small v-if="!isNew">(ID: {{ id }})</small></li>
            </ul>
        </template>

        <not-found v-if="notFound" />
        <template v-else>
            <div class="card mb-3" v-if="actualUserId">
                <div class="card-body d-flex align-items-center">
                    <img class="img-thumbnail rounded-circle s90 mr-3" :src="userAvatar" alt="">
                    <h5 class="mt-0"><router-link :to="{name: 'admin__users_edit', params: {id: actualUserId}}">{{ username }}</router-link></h5>
                </div>
            </div>
            <div v-else-if="!searching" class="p-5 d-flex justify-content-center card">
                <button class="btn btn-primary" @click.prevent="searching = true">Select user</button>
            </div>
            <user-search selectable v-else @selected="userSelected" />

            <form @submit.prevent="submit" v-show="username">
                <div class="form-group">
                    <img class="img-thumbnail rounded-circle s180" :src="avatar" alt="">
                </div>

                <div class="form-group">
                    <uploader
                        accept="image/*" default-text="Upload avatar" max="2000000" v-model="avatarFile"
                        @upload="onUpload" />
                    <small class="hint" v-show="uploadHint">{{ uploadHint }}</small>
                </div>

                <input-text required label="Full name" v-model="fullName" />
                <input-textarea required label="About" v-model="about" />

                <fieldset>
                    <legend>Links</legend>


                    <input-text inline label="LinkedIn link (optional)" v-model="linkLinkedIn" type="url" />
                    <input-text inline label="YouTube link (optional)" v-model="linkYt" type="url" />
                    <input-text inline label="Website link (optional)" v-model="linkWeb" type="url" />
                    <input-text inline label="Facebook profile link (optional)" v-model="linkFb" type="url" />
                    <input-text inline label="VK profile link (optional)" v-model="linkVk" type="url" />
                    <input-text inline label="Twitter link (optional)" v-model="linkTwitter" type="url" />
                </fieldset>

                <template v-if="isNew">
                    <hr>
                    <input-textarea required label="Reason for creating new teacher" v-model="comment" />
                </template>

                <div class="form-group">
                    <input type="submit" value="Save" class="btn btn-primary">
                </div>
            </form>
        </template>
    </admin-section>
</template>

<script lang="ts">
    import {Vue, Component, Prop, Watch, dto} from "@common";
    import AdminStoreComponent from "@admin/components/AdminStoreComponent";
    import AdminSection from "@admin/components/layout/AdminSection.vue";
    import InputText from "@common/components/forms/InputText.vue";
    import InputTextarea from "@common/components/forms/InputTextarea.vue";
    import {getError} from "@common/utils";
    import Uploader from "@common/components/utils/Uploader.vue";
    import Error from "@common/components/utils/Error.vue";
    import NotFound from "@common/components/pages/NotFound.vue";
    import UserSearch from "@admin/components/users/UserSearch.vue";

    @Component({
        name: "TeacherForm",
        components: {UserSearch, NotFound, Error, Uploader, InputTextarea, InputText, AdminSection}
    })
    export default class TeacherForm extends AdminStoreComponent {
        @Prop({ required: false }) id: number;
        @Prop({ required: false }) userId: number;

        inProgress = false;
        isNew = false;
        fullName: string = null;
        about: string = null;
        comment: string = null;
        error = null;
        avatar: string = null;
        avatarFile: File = null;
        uploadHint = null;
        oldId = null;
        notFound = false;
        searching = false

        linkYt = null
        linkVk = null
        linkFb = null
        linkLinkedIn = null
        linkTwitter = null
        linkWeb = null

        username: string = null
        userAvatar: string = null
        actualUserId: number = null;

        @Watch('id')
        async load() {
            if (this.oldId === this.id)
                return;
            this.inProgress = true
            let teacher: dto.AdminTeacherDto = null

            try
            {
                teacher = await this.admin.teachers.get(this.id)
            }
            catch (e) {
                this.notFound = true
                this.inProgress = false
                return
            }

            this.update(teacher)
            this.inProgress = false
        }

        userSelected(user: dto.UserDto) {
            this.actualUserId = user.id
            this.userAvatar = user.avatar
            this.username = user.name
            this.searching = false
            console.log(user)
        }

        update(teacher: dto.AdminTeacherDto) {
            this.fullName = teacher.fullName
            this.about = teacher.about
            this.avatar = teacher.avatar

            this.username = teacher.user.name
            this.userAvatar = teacher.user.avatar
            this.actualUserId = teacher.user.id

            this.linkFb = teacher.links.fb
            this.linkYt = teacher.links.yt
            this.linkVk = teacher.links.vk
            this.linkWeb = teacher.links.web
            this.linkLinkedIn = teacher.links.linkedIn
            this.linkTwitter = teacher.links.twitter
        }

        onUpload() {
            if (this.isNew)
                this.uploadHint = 'File will be uploaded on save'
            else
                this.uploadAvatar(this.id)
        }

        async uploadAvatar(id: number) {
            this.avatar = await this.admin.uploadTeacherAvatar({
                id,
                data: this.avatarFile
            })
            this.avatarFile = null
        }

        async submit() {
            this.inProgress = true

            try {
                if (this.isNew) {
                    let teacher = await this.admin.createTeacher({
                        fullName: this.fullName,
                        userId: this.userId,
                        about: this.about,
                        comment: this.comment
                    })

                    if (this.avatarFile)
                        await this.uploadAvatar(teacher.id)


                    await this.$router.replace({
                        name: 'admin__teachers_edit',
                        params: {
                            id: teacher.id+''
                        }
                    })
                } else {
                    let teacher = await this.admin.updateTeacher({
                        id: this.id,
                        data: {
                            fullName: this.fullName,
                            about: this.about,
                        }
                    })
                    this.update(teacher)
                }
            } catch (e) {
                this.error = getError(e)
            } finally {
                this.inProgress = false
            }
        }

        async loadUser() {
            let user = await this.admin.getUser(this.userId)
            this.username = user.name
            this.userAvatar = user.avatar
            if (user.teacher) {
                this.update(user.teacher)
                this.isNew = false
                this.oldId = user.teacher.id
                await this.$router.push({
                    name: 'admin__teachers_edit',
                    params: {
                        id: user.teacher.id+''
                    }
                })
            }
            this.inProgress = false
        }

        created() {
            if (typeof this.id === 'number' && !isNaN(this.id)) {
                this.load()
            } else {
                this.isNew = true

                if (typeof this.userId === 'number' && !isNaN(this.userId)) {
                    this.loadUser()
                }
            }
        }
    }
</script>

<style scoped lang="scss">
    .user-card {
        display: flex;
        align-items: center;

        &> header {
            margin: 10px;
        }
    }
</style>
