<template>
    <credit-card />
</template>

<script lang="ts">
    import {Component, Vue} from "vue-property-decorator";
    import CreditCard from "../../../../app/Services/Implementation/Payments/CreditCard.vue";

    type DummyPaymentRequest = {
        card: {
            number: string,
            expiryMonth: string,
            expiryYear: string
        }
    }
    @Component({
        components: {CreditCard}
    })
    export default class DummyPayment extends Vue {
        value: DummyPaymentRequest = null;
    }
</script>

<style scoped>

</style>
