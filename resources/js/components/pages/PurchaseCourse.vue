<template>
    <loader :promise="promise" no-value-message="Course not found" v-slot="{value}">
        <page title="Purchasing a course">
            <div class="purchase">
                <div class="purchase__body">
                    <div class="purchase__about">
                        {{value.name}}
                    </div>

                    <template v-if="value.has_preview">
                        <p>
                            This course has <b>free preview</b>, you can have access to free units for trial period and then
                            purchase a course if you wish. You can have a trial period only once.
                        </p>

                        <p>
                            Or, if you wish you can purchase course right now!
                        </p>
                    </template>

                    <template v-else>
                        <p>
                            You can buy this course for ${{value.price}} (VAT included) and have full access to all materials course has to offer!
                        </p>
                    </template>
                </div>
                <div class="purchase__price">
                    $ {{value.price}}
                </div>
                <div class="purchase__actions">
                    <button class="btn btn--primary" @click.prevent="purchase(value)">Purchase</button>
                    <button class="btn" @click.prevent="purchase(value, true)">Try it for free</button>
                </div>
            </div>
        </page>
    </loader>
</template>

<script lang="ts">
    import Page from "./Page.vue";
    import {PropValidator} from "vue/types/options";
    import {Course} from "../../models";
    import {api} from "../../api";
    import Loader from "../misc/Loader.vue";

    export default {
        name: "PurchaseCourse",
        components: {Loader, Page},
        data() {
            return {
                promise: null
            }
        },
        methods: {
            purchase(course: Course, preview = false) {
                if (preview && !course.has_preview)
                    return;

                this.$store.dispatch('courses/attend', {course, preview})
                    .then(r => console.log(r))
            },
            load() {
                this.promise = this.$store.dispatch('courses/getCourse', this.$route.params.id)
            }
        },
        created(): void {
            this.load()
        },
        watch: {
            $route() {
                this.load()
            }
        }
    }
</script>

<style scoped>

</style>
