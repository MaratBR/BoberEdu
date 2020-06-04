<template>
    <admin-section :in-progress="inProgress">
        <template v-slot:header v-if="!notFound">
            <ul class="breadcrumb">
                <li><router-link :to="{name: 'admin__teachers'}">Teachers</router-link></li>
                <li>{{ isNew ? 'new' : fullName }} <small v-if="!isNew">(ID: {{ id }})</small></li>
            </ul>
        </template>

        <not-found v-if="notFound">

        </not-found>
        <template v-else>
            <h3>User</h3>
            <template v-if="username">
                <div class="user-card">
                    <div class="avatar s60">
                        <img :src="'/storage/' + userAvatar" alt="" v-if="userAvatar">
                    </div>
                    <header>
                        <b><router-link :to="{name: 'admin__users_edit', params: {id: actualUserId}}">{{ username }}</router-link></b>
                        <small>(ID: {{ actualUserId }})</small>
                    </header>
                </div>
                <hr>
            </template>
            <error v-else error="User not found" />

            <form @submit.prevent="submit" v-show="username">
                <div class="control">
                    <div class="avatar">
                        <img :src="'/storage/' + avatar" alt="">
                    </div>
                </div>

                <div class="control">
                    <uploader
                        accept="image/*" default-text="Upload avatar" max="2000000" v-model="avatarFile"
                        @upload="onUpload" />
                    <small class="hint" v-show="uploadHint">{{ uploadHint }}</small>
                </div>

                <input-text required label="Full name" v-model="fullName" />
                <input-text required label="Document (passport) ID" v-model="passNum" />
                <input-textarea required label="About" v-model="about" />

                <fieldset>
                    <legend>Links</legend>
                    <input-text label="LinkedIn link (optional)" v-model="linkLinkedIn" />
                    <input-text label="YouTube link (optional)" v-model="linkYt" />
                    <input-text label="Website link (optional)" v-model="linkWeb" />
                    <input-text label="Facebook profile link (optional)" v-model="linkFb" />
                    <input-text label="VK profile link (optional)" v-model="linkVk" />
                    <input-text label="Twitter link (optional)" v-model="linkTwitter" />
                </fieldset>

                <template v-if="isNew">
                    <hr>
                    <input-textarea required label="Reason for creating new teacher" v-model="comment" />
                </template>

                <div class="control">
                    <input type="submit" value="Save">
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

    @Component({
        name: "TeacherForm",
        components: {NotFound, Error, Uploader, InputTextarea, InputText, AdminSection}
    })
    export default class TeacherForm extends AdminStoreComponent {
        @Prop({ required: false }) id: number;
        @Prop({ required: false }) userId: number;

        inProgress = false;
        isNew = false;
        fullName: string = null;
        passNum: string = null;
        about: string = null;
        comment: string = null;
        error = null;
        avatar: string = null;
        avatarFile: File = null;
        uploadHint = null;
        oldId = null;
        notFound = false;

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

        update(teacher: dto.AdminTeacherDto) {
            this.passNum = teacher.docId
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
                        passNum: this.passNum,
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
                            passNum: this.passNum
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
                this.loadUser()
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
