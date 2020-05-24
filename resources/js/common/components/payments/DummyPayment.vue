<template>
    <div class="payment payment--dummy">
        <p>DUMMY payment is not a payment gateaway it's basically just... DUMMY</p>
        <credit-card @input="receiveData" @invalid="$emit('invalid')" />
    </div>
</template>

<script lang="ts">
    import {Component, Vue} from "vue-property-decorator";
    import CreditCard from "./CreditCard.vue";
    import {CreditCardData} from "@common/api/dto";

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

        receiveData(data: CreditCardData) {
            this.$emit('input', {
                card: {
                    number: data.number,
                    expiryMonth: data.expiryMonth,
                    expiryYear: data.expiryYear,
                }
            })
        }
    }
</script>

<style scoped>

</style>
