<template>
    <loader v-if="!status" />
    <page v-else title="Purchasing course">
        <div class="notification" v-if="!status.enrolled">
            <p>
                Oops, it seems like you're not enrolled yet, gotta fix that!
            </p>
            <router-link :to="{name: 'course', params: {id: $route.params.id}}" class="btn btn--primary">Enroll</router-link>
        </div>

        <div class="notification" v-else-if="status.hasAccess">
            It seems like you already purchased this course.
        </div>

        <div class="payment" v-else>
            <tabs>
                <tab v-for="gateway in availableGateways" :name="gateway.name">
                    <component :is="gateway.component" :inline-template="true"
                               @invalid="ready = false"
                               @input="setPaymentPayload(gateway.name, $event)" />
                </tab>
            </tabs>
            <hr>
            <div class="payment__submit" v-show="ready">
                <p class="error" v-if="error">{{ error }}</p>
                <p>Your payment is ready to go!</p>
                <button @click="proceed">Proceed with payment</button>
            </div>
        </div>

    </page>
</template>

<script lang="ts">
    import Page from "./Page.vue";
    import Loader from "../misc/Loader.vue";
    import Error from "../misc/Error.vue";
    import MarkdownViewer from "../misc/MarkdownViewer.vue";
    import {Component, Vue, Watch} from "vue-property-decorator";
    import {Store} from "../../store";
    import {useStore} from "vuex-simple";
    import {dto} from "../../store/dto";
    import Tabs from "../Tabs.vue";
    import Tab from "../Tab.vue";
    import DummyPayment from "../payments/DummyPayment.vue";

    @Component({
        components: {DummyPayment, Tab, Tabs, MarkdownViewer, Error, Loader, Page}
    })
    export default class PurchaseCourse extends Vue {
        status: dto.EnrollmentStateDto = null;
        course: dto.CourseExDto = null;
        submitting = false;
        error = null;
        ready = false;
        paymentData: any = null;
        gateaway: any = null;
        availableGateways = [
            {
                name: 'Dummy',
                component: DummyPayment
            }
        ];

        store: Store = useStore(this.$store);

        setPaymentPayload(gateaway: string, data: any) {
            this.ready = true;
            this.gateaway = gateaway;
            this.paymentData = data;
        }

        async proceed() {
            this.submitting = true;
            let payment = await this.store.payments.pay({
                courseId: +this.$route.params.id,
                data: this.paymentData,
                gateway: this.gateaway
            });
            this.submitting = false;

            if (payment.success) {

            } else if (payment.redirect) {
                window.open(payment.redirect);
            } else {
                this.error = "Failed to perform operation";
            }
        }

        async load() {
            this.course = await this.store.courses.get(+this.$route.params.id)
            this.status = await this.store.courses.status(+this.$route.params.id)
        }

        created(): void {
            this.load()
        }

        @Watch('$route')
        routeChanged() {
            this.load()
        }
    }
</script>

<style lang="sass" scoped>
    @import "../../../sass/lib/config"


    .purchase
        display: grid
        grid-template-columns: auto 300px

        &__about
            background: #fafafa
            padding: 10px
            border: 1px solid #eee
            margin: 0 15px 0 0

        &__aside
            padding: 30px 10px 30px 30px
            border-left: 1px solid #eee

            & .btn
                width: 100%

        &__price
            font-size: 2em
            padding: 15px 5px
            background: rgba(0, 0, 0, 0.03)
            text-align: center

        &__links
            margin-top: 20px


        @media ($ss-breakpoint-tablet)
            grid-template-columns: 1fr
            grid-template-rows: auto auto

            &__aside
                border: 1px solid #eee
                background: #fafafa
                grid-row: 1

            &__body
                grid-row: 2

</style>
