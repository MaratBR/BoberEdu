<template>
    <div class="container">
        <div class="hero--phead">
            <ul class="breadcrumb">
                <li>Account</li>
                <li>Settings</li>
            </ul>
        </div>
        <tabs style-class="sidebar">
            <tab name="General">
                <form class="form">

                    <div class="form__control upload-avatar">
                        <div class="avatar s120">
                            <img :src="'/storage/' + avatar" alt="">
                        </div>
                        <uploader max="10000000" accept="image/*" v-model="avatarFile" @upload="uploadAvatar" />
                    </div>

                    <div class="form__control">
                        <label class="form__label" for="UsernameInput">Username</label>
                        <input
                            id="UsernameInput" type="text" class="input" v-model="username"
                            @input="checkUsernameThrottled">
                        <template v-if="username !== originalUsername">
                            <i class="fa fa-spinner fa-spin" v-if="usernameProgress"></i>
                            <i class="fa fa-times" v-else-if="usernameTaken"></i>
                            <i class="fa fa-check" v-else></i>
                            <button
                                @click.prevent="saveUsername"
                                class="btn btn--plain" v-show="!usernameProgress">Save</button>
                        </template>
                    </div>

                    <div class="form__control">
                        <label class="form__label" for="AboutInput">Tell people about yourself</label>
                        <textarea id="AboutInput" type="text" class="input" @input="showUpdateAboutButton = true" v-model="about"></textarea>
                        <button class="btn" v-if="showUpdateAboutButton" @click.prevent="updateAbout">Update</button>
                    </div>

                    <div class="form__control">
                        <label class="form__label" for="EmailInput">Email</label>
                        <input disabled id="EmailInput" type="text" class="input" :value="email">
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

    @Component({
        components: {Uploader, Tab, Tabs}
    })
    export default class ProfileSettings extends StoreComponent {
        username: string = null;
        about: string = null;
        email: string = null;
        avatar: string = null;
        sex: dto.Sex = null;

        avatarFile: File = null;
        usernameTaken: boolean = null;
        originalUsername: string = null;
        checkUsernameThrottled = throttle(this.checkUsername, 500)
        usernameProgress: boolean = false;
        showUpdateAboutButton = false;

        async load() {
            let settings = await this.store.users.settings()
            this.username = settings.name
            this.sex = settings.sex
            this.about = settings.about
            this.avatar = settings.avatar

            this.originalUsername = settings.name
        }

        async saveUsername() {
            let username = this.username.trim()
            if (username.toLowerCase() == this.originalUsername.toLowerCase())
                return;

            this.usernameProgress = true
            await this.store.users.update({
                id: this.store.auth.user.id,
                req: {
                    name: username
                }
            })
            this.usernameProgress = false
            this.originalUsername = this.username = username
        }

        async updateAbout() {
            this.showUpdateAboutButton = false;
            await this.store.users.update({
                id: this.store.auth.user.id,
                req: {
                    about: this.about
                }
            })
        }

        async checkUsername() {
            this.usernameProgress = true
            let username = this.username.trim()
            this.usernameTaken = await this.store.users.usernameIsTaken(username)
            this.usernameProgress = false
        }

        async uploadAvatar() {
            this.avatar = await this.store.users.uploadAvatar(this.avatarFile)
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
