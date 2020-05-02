<template>
    <loader v-if="!profile" />
    <div v-else class="profile container">
        <header class="profile__heading user-heading hero--phead">
            <div class="user-heading__avatar avatar">
                <img src="https://cdn.eso.org/images/thumb300y/eso1907a.jpg" alt="">
            </div>

            <div class="user-heading__about rest">
                <div>
                    <span class="usrinf--username username">{{ profile.user.name }}</span><br>
                    <span class="usrinf--since">Joined at {{ joinedAt }}</span>
                    <div class="usrinf--status">
                        <p v-if="!setStatus">{{ profile.user.status || 'no status set' }}</p>
                        <div v-else class="edit-status">
                            <textarea v-model="newStatus" class="edit-status__input input" />
                        </div>
                        <button @click="editStatus"
                                v-if="store.auth.isAuthenticated && profile.user.id === store.auth.user.id && !setStatus">
                            <i class="fa fa-edit"></i> edit
                        </button>
                        <button v-else-if="setStatus" @click="updateStatus">
                            <i class="fa fa-check"></i> done
                        </button>
                    </div>
                </div>

                <div class="user-heading__tabs" v-if="store.auth.isAuthenticated && profile.user.id === store.auth.user.id">
                    <router-link :to="{name: 'profile_courses'}">My courses</router-link>
                    <router-link :to="{name: 'profile_payments'}">My payments</router-link>
                    <router-link :to="{name: 'profile_settings'}">Settings</router-link>
                </div>
            </div>
        </header>

        <component :is="editComp" v-if="editComp" :user="profile.user" />

        <div class="profile__about">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad animi consequuntur dolor doloribus dolorum ducimus, eius eos explicabo illo iure nam nemo numquam perferendis possimus quo sequi sit voluptas, voluptatibus.
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci alias aut beatae consectetur earum eveniet itaque maxime minima neque, nulla placeat possimus quia quisquam quo repudiandae saepe tenetur voluptatibus voluptatum?
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci alias aut beatae consectetur earum eveniet itaque maxime minima neque, nulla placeat possimus quia quisquam quo repudiandae saepe tenetur voluptatibus voluptatum?
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
            <p v-else>There's not courses yet</p>
        </div>
    </div>
</template>

<script lang="ts">
    import {Component, Vue, Watch} from "vue-property-decorator";
    import Loader from "../../misc/Loader.vue";
    import {Store} from "../../../store";
    import {useStore} from "vuex-simple";
    import {dto} from "../../../store/dto";

    @Component({
        components: {Loader}
    })
    export default class Profile extends Vue {
        profile: dto.UserProfileDto = null;
        editComp = null;
        setStatus: boolean = false;
        newStatus: string = null;


        store: Store = useStore(this.$store);

        async init() {
            this.profile = await this.store.users.profile(+this.$route.params.id)
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
                await this.store.users.setStatus(status);
                await this.init()
            }
        }

        resetStatus() {
            this.setStatus = false;
            this.newStatus = null;
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
    @import "../../../../sass/lib/config";

    .usrinf {
        &--username {
            font-size: 2em;
        }

        &--since {
            color: #888;
            font-size: 0.9em;
        }

        &--status {
            color: #555;
            padding-left: 10px;

            & > button {
                border: none;
                background: none;
                cursor: pointer;
            }
        }
    }

    .profile {
        &__heading {
            margin: 20px 0;
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
            position: absolute;
            bottom: 0;

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
