<template>
    <page title="Purchasing a course">
        <div class="purchase">
            <div class="purchase__body">
                <template v-if="course.has_preview">
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
                        You can buy this course for ${{course.price}} (VAT included) and have full access to all materialc course has to offer!
                    </p>
                </template>
            </div>
            <div class="purchase__price">
                $ {{course.price}}
            </div>
            <div class="purchase__actions">
                <button class="btn btn--primary" @click.prevent="purchase()">Purchase</button>
                <button class="btn" @click.prevent="purchase(true)">Try it for free</button>
            </div>
        </div>
    </page>
</template>

<script lang="ts">
    import Page from "./Page.vue";
    import {PropValidator} from "vue/types/options";
    import {Course} from "../../models";
    import {api} from "../../api";

    export default {
        name: "PurchaseCourse",
        components: {Page},
        props: {
            course: {
                required: true,
                type: Object
            } as PropValidator<Course>
        },
        methods: {
            purchase(preview = false) {
                if (preview && !this.course.has_preview)
                    return

                api.courses
            }
        }
    }
</script>

<style scoped>

</style>
