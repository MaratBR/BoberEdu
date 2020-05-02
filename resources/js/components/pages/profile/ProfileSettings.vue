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
                    <div class="form__control">
                        <label class="form__label" for="UsernameInput">Username</label>
                        <input
                            id="UsernameInput" type="text" class="input" v-model="data.username"
                            @input="checkUsernameThrottled">
                        <template v-if="data.username !== originalUsername">
                            <i class="fa fa-spinner fa-spin" v-if="data.usernameProgress"></i>
                            <i class="fa fa-times" v-else-if="data.taken"></i>
                            <i class="fa fa-check" v-else></i>
                            <button
                                @click="saveUsername"
                                class="btn btn--plain" v-show="!data.usernameProgress">Save</button>
                        </template>
                    </div>

                    <div class="form__control">
                        <label class="form__label" for="AboutInput">Tell people about yourself</label>
                        <textarea id="AboutInput" type="text" class="input">{{ data.about }}</textarea>
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

    type ProfileSettingsData = {
        username?: string,
        about?: string,
        email?: string,
        sex?: dto.Sex,
        taken?: boolean,
        usernameProgress?: boolean
    }

    @Component({
        components: {Tab, Tabs}
    })
    export default class ProfileSettings extends Vue {
        store = useStore<Store>(this.$store)
        settings: dto.UserSettingsDto = null;
        data: ProfileSettingsData = {}
        originalUsername: string = null;
        checkUsernameThrottled = throttle(this.checkUsername, 500)

        async load() {
            let settings = await this.store.users.settings()
            this.data = {
                username: settings.name,
                sex: settings.sex,
                email: settings.email,
                about: settings.about,
            }
            this.originalUsername = settings.name
        }

        async saveGeneral() {
        }

        async saveUsername() {
            let username = this.data.username.trim()
            if (username.toLowerCase() == this.originalUsername.toLowerCase())
                return;

            Vue.set(this.data, 'usernameProgress', true)
            await this.store.users.update({
                id: this.store.auth.user.id,
                req: {
                    name: username
                }
            })
            Vue.set(this.data, 'usernameProgress', false)
        }

        async checkUsername() {
            Vue.set(this.data, 'usernameProgress', true)
            let username = this.data.username.trim()
            Vue.set(this.data, 'taken', await this.store.users.usernameIsTaken(username))
            Vue.set(this.data, 'usernameProgress', false)
        }

        mounted() {
            this.load()
        }
    }
</script>

<style scoped>
    .fa-times {
        color: red;
    }

    .fa-check {
        color: green;
    }
</style>
