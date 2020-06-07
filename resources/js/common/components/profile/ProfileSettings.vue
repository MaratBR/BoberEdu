<template>
    <div class="container">
        <ul class="breadcrumb breadcrumb-clear">
            <li class="breadcrumb-item">
                <router-link :to="{name: 'profile', params: {id: store.user.id}}">Account</router-link>
            </li>
            <li class="breadcrumb-item active">Settings</li>
        </ul>
        <tabs style-class="sidebar">
            <tab name="General">
                <form class="form">

                    <div class="form-group upload-avatar">
                        <img class="img-thumbnail rounded-circle s210" :src="avatar" alt="">
                        <uploader max="10000000" accept="image/*" v-model="avatarFile" @upload="uploadAvatar" />
                    </div>

                    <div class="form-group">
                        <label for="UsernameInput">Username</label>
                        <input
                            id="UsernameInput" type="text" class="form-control" v-model="username"
                            @input="checkUsernameThrottled">
                        <template v-if="username !== originalUsername">
                            <i class="fas fa-spinner fa-spin" v-if="usernameProgress"></i>
                            <i class="fas fa-times" v-else-if="usernameTaken"></i>
                            <i class="fas fa-check" v-else></i>
                            <button
                                @click.prevent="saveUsername"
                                class="btn" v-show="!usernameProgress">Save</button>
                        </template>
                    </div>

                    <div class="form-group">
                        <input-textarea v-model="about" label="Tell people about yourself" @input="showUpdateAboutButton = true" />
                        <button class="btn" v-if="showUpdateAboutButton" @click.prevent="updateAbout">Update</button>
                    </div>

                    <div class="form-group">
                        <input-text v-model="email" protected />
                    </div>
                </form>
            </tab>
        </tabs>
    </div>
</template>

<script lang="ts">


    import {StoreComponent, Tab, Tabs, Uploader} from "@common/components/utils";
    import {Component, dto, Vue} from "@common";
    import {throttle} from "@app/utils";
    import InputText from "@common/components/forms/InputText.vue";
    import InputTextarea from "@common/components/forms/InputTextarea.vue";

    @Component({
        components: {InputTextarea, InputText, Uploader, Tab, Tabs}
    })
    export default class ProfileSettings extends StoreComponent {
        username: string = null;
        about: string = null;
        email: string = null;
        avatar: string = null;

        avatarFile: File = null;
        usernameTaken: boolean = null;
        originalUsername: string = null;
        checkUsernameThrottled = throttle(this.checkUsername, 500)
        usernameProgress: boolean = false;
        showUpdateAboutButton = false;

        async load() {
            let settings = await this.store.userSettings()
            this.username = settings.name
            this.about = settings.about
            this.avatar = settings.avatar
            this.email = settings.email
            this.originalUsername = settings.name
        }

        async saveUsername() {
            let username = this.username.trim()
            if (username.toLowerCase() == this.originalUsername.toLowerCase())
                return;

            this.usernameProgress = true
            await this.store.updateUser({
                name: username
            })
            this.usernameProgress = false
            this.originalUsername = this.username = username
        }

        async updateAbout() {
            this.showUpdateAboutButton = false;
            await this.store.updateUser({
                about: this.about
            })
        }

        async checkUsername() {
            this.usernameProgress = true
            let username = this.username.trim()
            this.usernameTaken = await this.store.usernameIsTaken(username)
            this.usernameProgress = false
        }

        async uploadAvatar() {
            this.avatar = await this.store.uploadAvatar(this.avatarFile)
        }

        mounted() {
            this.load()
        }
    }
</script>

<style scoped lang="scss">
    .fa-times {
        color: red;
    }

    .fa-check {
        color: green;
    }

    .upload-avatar {
        & > .avatar {
            margin-bottom: 20px;
        }
    }
</style>
