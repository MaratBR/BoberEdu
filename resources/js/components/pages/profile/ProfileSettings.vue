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
                        <input id="UsernameInput" type="text" class="input">
                    </div>

                    <div class="form__control">
                        <label class="form__label" for="AboutInput">Tell people about yourself</label>
                        <textarea id="AboutInput" type="text" class="input"></textarea>
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
    @Component({
        components: {Tab, Tabs}
    })
    export default class ProfileSettings extends Vue {
        async saveGeneral() {

        }
    }
</script>

<style scoped>
</style>
