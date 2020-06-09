<template>
    <div class="container pt-4">
        <div class="d-flex align-items-center flex-column flex-md-row">
            <img src="/assets/brand/smart_bober.png" class="s-w270" alt="">
            <div class="p-1">
                <h4><span class="display-4">Smart? We need your brains!</span><sup>*</sup></h4>
                <span class="text-muted">*literally</span>
            </div>
        </div>

        <div class="row">
            <div class="col border-right" v-if="canSendApplication">
                <form action="#" @submit.prevent="submit" v-if="store.isAuthenticated">
                    <input-text required label="Full name" v-model="fullName" />
                    <input-text required label="Education" v-model="education" />
                    <input-text required label="Location" v-model="location" />
                    <input-text required label="Degree" v-model="degree" />
                    <input-textarea required label="Anything else we need to know" v-model="extra" />

                    <div class="input-group">
                        <loader v-if="submitting" />
                        <input type="submit" class="btn btn-primary" v-else>
                    </div>
                </form>
                <div v-else>
                    <p>To send teacher application you need to logged in</p>
                    <router-link class="btn btn-primary" :to="{name: 'login'}">Log in</router-link>
                </div>
            </div>

            <div class="col d-flex justify-content-center align-items-center p-5">
                <div class="d-flex flex-column align-items-center">
                    <template v-if="state === null">
                        <i class="fas fa-hand-point-left m-3 display-3"></i>
                        <span class="text-muted">You haven't submitted any forms yet</span>
                    </template>
                    <template v-else-if="state === 'approved'">
                        <i class="fas fa-check m-3 display-3"></i>
                        <span class="text-muted">Your application has been approved!</span>
                    </template>
                    <template v-else-if="state === 'rejected'">
                        <i class="fas fa-times m-3 display-3"></i>
                        <span class="text-muted">Your application has been rejected</span>
                    </template>
                    <template v-else-if="state === 'awaiting'">
                        <i class="fas fa-hourglass m-3 display-3"></i>
                        <span class="text-muted">Your application is awaiting review</span>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
    import {Vue, Component, dto} from "@common";
    import InputText from "@common/components/forms/InputText.vue";
    import InputTextarea from "@common/components/forms/InputTextarea.vue";
    import {StoreComponent} from "@common/components/utils";
    import Loader from "@common/components/utils/Loader.vue";

    @Component({
        name: "TeacherApprovalForm",
        components: {Loader, InputTextarea, InputText}
    })
    export default class TeacherApprovalForm extends StoreComponent {
        degree: string = null;
        fullName: string = null;
        location: string = null;
        extra: string = null;
        education: string = null;
        state: dto.TeacherApprovalState = null
        submitting: boolean = false

        get canSendApplication() {
            return this.state == 'rejected' || this.state === null
        }

        async update() {
            this.state = await this.store.getTeacherApprovalState()
        }

        async submit() {
            this.submitting = true

            try {
                await this.store.sendTeacherApprovalForm({
                    degree: this.degree,
                    education: this.education,
                    extra: this.extra,
                    fullName: this.fullName,
                    location: this.location
                })
            } catch (e) {
                return
            } finally {
                this.submitting = false
            }

            await this.update()
        }

        created() {
            this.update()
        }
    }
</script>

<style scoped lang="scss">

</style>
