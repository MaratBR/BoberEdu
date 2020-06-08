<template>
    <admin-section :in-progress="inProgress" :not-found="notFound">
        <template v-slot:header>
            <ul class="breadcrumb breadcrumb-clear">
                <li class="breadcrumb-item">Users</li>
                <li class="breadcrumb-item active" v-if="isNew">New</li>
                <li class="breadcrumb-item active" v-else>{{ name }} <small>(ID: {{ id }})</small></li>
            </ul>
        </template>

        <form @submit.prevent="submit">
            <img class="img-thumbnail rounded-circle s120" :src="avatar" alt="">
            <div class="form-group">
                <uploader accept="image/*" max="1000000" default-text="Upload avatar"
                          :uploading="avatarIsUploading" @upload="upload" v-model="avatarFile" />
                <small v-if="uploadHint">{{ uploadHint }}</small>
            </div>

            <input-text v-model="name" label="Username" required />
            <input-text v-model="displayName" label="Display name" hint="The way system address user" />

            <input-text ref="emailEditor"
                @unlocked="email = actualEmail" v-model="email" label="Email" :protected="!isNew"
                placeholder="******@******.***" :required="true" />

            <input-text v-if="isNew" label="Password" required v-model="password" />

            <input-textarea v-model="about" label="About" v-if="!isNew" />
            <input-textarea v-model="status" label="Status" v-if="!isNew" />
            <input-checkbox v-model="isAdmin" label="Has admin permissions" />
            <input-textarea v-model="promotionReason" v-if="isNew ? isAdmin : isAdmin !== isAdminOriginal"
                label="Provide explanation for promotion/demotion" required />

            <error v-if="id === store.user.id && (isAdminOriginal === true) && !isAdmin"
                   error="Warning: you will remove your own administrator permissions you will not be able to access admin panel anymore" />


            <error :error="error" v-if="error" />

            <div class="form-group">
                <input type="submit" value="Save" class="btn btn-primary">
            </div>
        </form>
    </admin-section>
</template>

<script lang="ts">
    import {Vue, Component, Prop, Watch, dto, requests} from "@common";
    import AdminSection from "@admin/components/layout/AdminSection.vue";
    import AdminStoreComponent from "@admin/components/AdminStoreComponent";
    import Uploader from "@common/components/utils/Uploader.vue";
    import InputText from "@common/components/forms/InputText.vue";
    import InputTextarea from "@common/components/forms/InputTextarea.vue";
    import Error from "@common/components/utils/Error.vue";
    import {getError} from "@common/utils";
    import draggable from "vuedraggable";
    import InputCheckbox from "@common/components/forms/InputCheckbox.vue";

    @Component({
        name: "UserForm",
        components: {InputCheckbox, Error, InputTextarea, InputText, Uploader, AdminSection, draggable}
    })
    export default class UserForm extends AdminStoreComponent {
        @Prop({type: Number}) id: number;
        name: string = null
        about: string = null
        status: string = null
        email: string = null
        actualEmail: string = null
        displayName: string = null
        isAdmin: boolean = null
        isAdminOriginal: boolean = null
        promotionReason: string = null
        password: string = null
        avatar: string = null
        avatarFile: File = null
        avatarIsUploading: boolean = false
        uploadHint: string = null
        error = null;

        isNew: boolean = false
        notFound = false
        inProgress = true

        @Watch('id')
        async load() {
            let user: dto.AdminUserDto
            try {
                user = await this.admin.getUser(this.id)
            } catch (e) {
                this.notFound = true
                return
            }

            this.name = user.name
            this.about = user.about
            this.status = user.status
            this.actualEmail = user.email
            this.avatar = user.avatar
            this.displayName = user.displayName
            this.isAdmin = this.isAdminOriginal = user.admin


            this.inProgress = false
        }

        async uploadAvatar(id: number) {
            if (this.avatarFile)
                this.avatar = await this.admin.uploadUserAvatar({
                    id, data: this.avatarFile
                })
        }

        async upload() {
            if (this.isNew) {
                this.uploadHint = 'File will be uploaded on save'
                return
            }

            this.avatarIsUploading = true
            this.avatar = await this.admin.uploadUserAvatar({
                id: this.id,
                data: this.avatarFile
            })
            this.avatarFile = null
            this.avatarIsUploading = false
        }

        created() {
            if (typeof this.id === 'number' && !isNaN(this.id)) {
                this.load()
            } else {
                this.isNew = true
                this.inProgress = false
            }
        }

        async submit() {
            this.inProgress = true
            try {
                if (this.isNew) {
                    let data: requests.Register = {
                        name: this.name,
                        displayName: this.displayName,
                        password: this.password,
                        email: this.email
                    }

                    let user: dto.AdminUserDto
                    try
                    {
                        user = await this.admin.createUser(data)

                        if (this.avatarFile)
                            await this.uploadAvatar(user.id)

                        if (this.isAdmin)
                            await this.admin.promoteUser({id: user.id, data: {admin: true, reason: this.promotionReason}})
                    }
                    catch (e) {
                        this.error = getError(e)
                        return
                    }
                    this.isNew = false
                    this.error = null
                    await this.$router.push({
                        name: 'admin__users_edit',
                        params: {
                            id: user.id+''
                        }
                    })
                } else {
                    let data: requests.UpdateUser = {
                        name: this.name,
                        about: this.about,
                        displayName: this.displayName,
                        status: this.status
                    }
                    if ((this.$refs.emailEditor as Vue).$data.unlocked) {

                    }

                    if (this.isAdminOriginal !== this.isAdmin)
                        await this.admin.promoteUser({id: this.id, data: {admin: this.isAdmin, reason: this.promotionReason}})

                    await this.admin.updateUser({
                        id: this.id,
                        data
                    })
                }
            } catch (e) {

            } finally {
                this.inProgress = false
            }
        }
    }
</script>

<style scoped lang="scss">
    .roles {
        display: flex;
    }

    .role {
        display: inline-block;
        padding: 5px 5px 0 5px;
        border-radius: 4px;
        font-size: 1.06em;
        margin: 5px;
        border: 1px solid #ccc;

        & > label {
            cursor: pointer;
        }

        &:hover {
            background: rgba(black, 0.04);
        }
    }
</style>
