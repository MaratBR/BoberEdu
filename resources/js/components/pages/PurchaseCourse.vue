<template>
    <page title="Purchasing a course">
        <div class="purchase">
            <loader :promise="promise" v-slot="{value}">
                <template v-if="value.course">
                    <div class="purchase__body">
                        <div class="purchase__about">
                            {{value.course.name}}
                        </div>

                        <template v-if="value.attendanceStatus === 'no'">
                            <template v-if="value.course.has_preview">
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
                        </template>

                        <template v-else-if="value.attendanceStatus === 'yes'">
                            <p>You already attend this course. Enjoy!</p>
                        </template>

                        <template v-else-if="value.attendanceStatus === 'awaiting_payment'">
                            <p>All done, we're awaiting for your confirmation</p>
                        </template>

                    </div>
                    <div class="purchase__price">
                        $ {{value.course.price}}
                    </div>
                    <div class="purchase__actions" v-if="value.attendanceStatus !== 'yes'">
                        <button class="btn btn--primary" @click.prevent="purchase(value.course)">Purchase</button>
                        <button class="btn" @click.prevent="purchase(value.course, true)">Try it for free</button>
                    </div>
                </template>
                <template v-else>
                    <error>
                        Ooops, it look like course with id = {{$route.params.id}} not found
                    </error>
                </template>
            </loader>
        </div>
    </page>
</template>

<script lang="ts">
    import Page from "./Page.vue";
    import {PropValidator} from "vue/types/options";
    import {Course} from "../../models";
    import {api} from "../../api";
    import Loader from "../misc/Loader.vue";
    import Error from "../misc/Error.vue";

    export default {
        name: "PurchaseCourse",
        components: {Error, Loader, Page},
        data() {
            return {
                promise: null
            }
        },
        methods: {
            purchase(course: Course, preview = false) {
                if (preview && !course.has_preview)
                    return;

                this.$store.dispatch('courses/purchase', {course, preview})
                    .then(r => {
                        this.submitPurchase(r.attendance.id)
                    })
            },
            submitPurchase(attendanceId: number) {
                this.$store.dispatch('courses/submitPurchase', attendanceId)
                    .then(r => console.log(r))
            },

            load() {
                this.promise = this.$store.dispatch('courses/getPurchaseModel', this.$route.params.id)
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
