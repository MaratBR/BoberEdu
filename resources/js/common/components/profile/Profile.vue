<template>
    <not-found v-if="notFound" />
    <loader v-else-if="!profile" />
    <div class="profile" v-else>
        <div class="container-fluid profile-header pt-3">
            <div class="container pt-3">
                <div class="row">
                    <div class="col-lg-4 col-md-12 d-flex justify-content-center">
                        <div class="img-thumbnail mb-4">
                            <img :src="profile.user.avatar" class="s270">
                        </div>
                    </div>

                    <div class="col-lg-8 col-md-12 d-flex flex-column justify-content-center">
                        <h5 class="username mb-0">
                            {{ profile.user.name }}
                        </h5>
                        <span>Joined at {{ joinedAt }}</span>
                        <div class="form-group">
                            <p v-if="!setStatus" class="ml-3 mt-2 form-control form-control-plaintext">{{profile.user.status}}</p>
                            <div v-else class="edit-status">
                                <input v-model="newStatus" class="edit-status__input form-control" />
                            </div>
                            <button @click="editStatus" class="btn btn-sm"
                                    v-if="store.isAuthenticated && profile.user.id === store.user.id && !setStatus">
                                <i class="fas fa-edit"></i> edit
                            </button>
                            <button v-else-if="setStatus" class="btn btn-sm" @click="updateStatus">
                                <i class="fas fa-check"></i> done
                            </button>
                        </div>

                        <div class="user-heading__tabs d-flex" v-if="store.isAuthenticated && profile.user.id === store.user.id">
                            <router-link :to="{name: 'profile_courses'}">My courses</router-link>
                            <router-link :to="{name: 'profile_payments'}">My payments</router-link>
                            <router-link :to="{name: 'profile_settings'}">Settings</router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="profile__about mt-5">
                <markdown-viewer :value="profile.user.about" />
            </div>

            <div class="profile__courses">
                <h2>{{ profile.user.name }}'s courses</h2>

                <table class="table" v-if="profile.courses.length">
                    <tr v-for="c in profile.courses">
                        <td>
                            <router-link :to="{name: 'course', params: {id: c.course.id}}">{{ c.course.name }}</router-link>
                            <span class="badge" v-if="c.activated">PURCHASED</span>
                        </td>
                        <td><i>since</i> {{ new Date(c.since).toDateString() }}</td>
                    </tr>
                </table>
                <p v-else>There's no courses yet</p>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
    import {Component, dto, Vue, Watch} from "@common";
    import {Loader, StoreComponent} from "@common/components/utils";
    import MarkdownViewer from "@common/components/utils/MarkdownViewer.vue";
    import NotFound from "@common/components/pages/NotFound.vue";

    @Component({
        components: {NotFound, MarkdownViewer, Loader}
    })
    export default class Profile extends StoreComponent {
        profile: dto.UserProfileDto = null;
        setStatus: boolean = false;
        newStatus: string = null;
        notFound = false

        async init() {
            try {
                this.profile = await this.store.userProfile(+this.$route.params.id)
            } catch (e) {
                this.notFound = true
            }
        }

        created() {
            this.init()
        }

        get joinedAt() {
            return new Date(this.profile.user.joinedAt).toDateString()
        }

        editStatus() {
            this.newStatus = this.profile.user.status;
            this.setStatus = true;
        }

        async updateStatus() {
            this.setStatus = false;
            let status = this.newStatus.trim();
            if (status !== this.profile.user.status) {
                this.profile.user.status = status;
                await this.store.setUserStatus(status);
                await this.init()
            }
        }

        @Watch('$route')
        onRouteChanged() {
            if (this.$route.name === 'profile') {
                this.init()
            }
        }
    }
</script>

<style scoped lang="scss">
    @import "../../../../sass/config";

    .profile-header {
        background-image: repeating-linear-gradient(transparent, transparent 100px, white 100px, white 120px),
            repeating-linear-gradient(90deg, transparent, transparent 100px, white 100px, white 120px),
            linear-gradient(45deg, #85daff, #0043ff);

        & > .container {
            background: white;
            box-shadow: 0 4px 12px -2px #b9b9b9;
            border: 1px solid #e7e7e7;
            border-bottom: none;
            position: relative;
            top: 20px;
        }
    }

    .course {
        background: #f5f5f5;
        margin-bottom: 10px;
        padding: 5px;
    }

    .user-heading {
        position: relative;

        &__about {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 10px 0 40px 20px;
            min-width: 250px;
        }

        &__tabs {
            @media (min-width: 992px) {
                position: absolute;
                bottom: 0;
            }

            & > a, & > button {
                background: transparent;
                border: none;
                text-decoration: none;
                color: #444;
                padding: 10px;
                display: inline-block;
                border-bottom: 4px solid #eee;
                margin-right: 10px;
                transition: .2s;

                &:hover {
                    border-color: black;
                    color: black;
                }
            }
        }
    }

    .edit-status {
        margin: 10px 0;
    }
</style>
