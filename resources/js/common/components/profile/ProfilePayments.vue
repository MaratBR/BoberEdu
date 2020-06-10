<template>
    <div class="container">
        <ul class="breadcrumb breadcrumb-clear">
            <li class="breadcrumb-item">
                <router-link :to="{name: 'profile', params: {id: store.user.id}}">Account</router-link>
            </li>
            <li class="breadcrumb-item active">Payments</li>
        </ul>
        <data-presenter :pagination="payments">
            <template v-slot:table-header>
                <th>Transaction UID</th>
                <th>Success</th>
                <th>Title</th>
                <th>Amount</th>
                <th>Via</th>
                <th>User-Agent</th>
            </template>

            <template v-slot="{uid, gateaway, success, title, amount, userAgent}">
                <td>{{uid}}</td>
                <td>
                    <bool-presenter :value="success" />
                </td>
                <td>{{title}}</td>
                <td>{{amount}}</td>
                <td>{{gateaway}}</td>
                <td style="width: 20%;">{{userAgent}}</td>
            </template>
        </data-presenter>
    </div>
</template>

<script lang="ts">
    import {Vue, Component, dto} from "@common";
    import {StoreComponent} from "@common/components/utils";
    import DataPresenter from "@common/components/forms/DataPresenter.vue";
    import BoolPresenter from "@common/components/forms/BoolPresenter.vue";

    @Component({
        name: "ProfilePayments",
        components: {BoolPresenter, DataPresenter}
    })
    export default class ProfilePayments extends StoreComponent {
        payments: dto.PaginationDto<dto.PaymentDto> = null

        async load() {
            this.payments = null
            this.payments = await this.store.getPayments()
        }

        created() {
            this.load()
        }
    }
</script>

<style scoped lang="scss">

</style>
