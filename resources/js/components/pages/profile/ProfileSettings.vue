<template>
    <div class="container">
        <div class="hero--phead">
            <h2>
                <ul class="breadcrumb">
                    <li>Account</li>
                    <li>Settings</li>
                </ul>
            </h2>
        </div>
        <tabs style-class="sidebar">
            <tab name="General">
                <form @submit.prevent="saveGeneral" class="form">

                    <div class="form__control upload-avatar">
                        <div class="avatar s120">
                            <img :src="'/storage/avatars/' + data.avatar" alt="">
                        </div>
                        <uploader max="10000000" @uploaded="data.avatar = $event" :uploader="avatarUploader" accept="image/*" />
                    </div>

                    <div class="form__control">
                        <label class="form__label" for="UsernameInput">Username</label>
                        <input
                            id="UsernameInput" type="text" class="input" v-model="data.username"
                            @input="checkUsernameThrottled">
                        <template v-if="data.username !== originalUsername">
                            <i class="fa fa-spinner fa-spin" v-if="usernameProgress"></i>
                            <i class="fa fa-times" v-else-if="data.taken"></i>
                            <i class="fa fa-check" v-else></i>
                            <button
                                @click.prevent="saveUsername"
                                class="btn btn--plain" v-show="!usernameProgress">Save</button>
                        </template>
                    </div>

                    <div class="form__control">
                        <label class="form__label" for="AboutInput">Tell people about yourself</label>
                        <textarea id="AboutInput" type="text" class="input" @input="showUpdateAboutButton = true" v-model="data.about"></textarea>
                        <button class="btn" v-if="showUpdateAboutButton" @click.prevent="updateAbout">Update</button>
                    </div>

                    <div class="form__control">
                        <label class="form__label" for="EmailInput">Email</label>
                        <input disabled id="EmailInput" type="text" class="input" :value="data.email">
                    </div>
                </form>
            </tab>
        </tabs>
    </div>
</template>

<script lang="ts">
    import {Component, Prop, Vue} from "vue-property-decorator";
    import Tabs from "../../Tabs.vue";
    import Tab from "../../Tab.vue";
    import {useStore} from "vuex-simple";
    import {Store} from "../../../store";
    import {dto} from "../../../store/dto";
    import {throttle} from "../../../utils";
    import Uploader from "../../misc/Uploader.vue";

    type ProfileSettingsData = {
        username?: string,
        about?: string,
        email?: string,
        sex?: dto.Sex,
        taken?: boolean,
        avatar?: string
    }

    @Component({
        components: {Uploader, Tab, Tabs}
    })
    export default class ProfileSettings extends Vue {
        store = useStore<Store>(this.$store)
        data: ProfileSettingsData = {}
        originalUsername: string = null;
        checkUsernameThrottled = throttle(this.checkUsername, 500)
        usernameProgress: boolean = false;
        showUpdateAboutButton = false;
        avatarUploader = this.store.users.getAvatarUploader()

        async load() {
            let settings = await this.store.users.settings()
            this.data = {
                username: settings.name,
                sex: settings.sex,
                email: settings.email,
                about: settings.about,
                avatar: settings.avatar
            }
            this.originalUsername = settings.name
        }

        async saveUsername() {
            let username = this.data.username.trim()
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
            this.originalUsername = this.data.username = username
        }

        async updateAbout() {
            this.showUpdateAboutButton = false;
            await this.store.users.update({
                id: this.store.auth.user.id,
                req: {
                    about: this.data.about
                }
            })
        }

        async checkUsername() {
            this.usernameProgress = true
            let username = this.data.username.trim()
            Vue.set(this.data, 'taken', await this.store.users.usernameIsTaken(username))
            this.usernameProgress = false
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
