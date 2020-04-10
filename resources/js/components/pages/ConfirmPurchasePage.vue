<template>
    <page title="Purchase confirmation">
        <loader :promise="promise" v-slot="{value}">
            <pre>{{value}}</pre>
            <p>Payment status: <span class="status">{{value.status}}</span></p>
            <template v-if="value.status === 'success'">
                <p>You have successfully purchased, a course</p>
            </template>
        </loader>
    </page>
</template>

<script lang="ts">
    import Page from "./Page.vue";
    import Loader from "../misc/Loader.vue";
    import {Vue, Component} from "vue-property-decorator";
    import {Purchase} from "../../store/modules/CoursesModule";
    import {Store} from "../../store";
    import {useStore} from "vuex-simple";

    @Component({
        components: {Loader, Page}
    })
    export default class ConfirmPurchasePage extends Vue {
        promise: Promise<Purchase>;

        store: Store = useStore(this.$store);

        created(): void {
            this.promise = this.store.purchases.check(+this.$route.params.id)
        }
    }
</script>

<style scoped>

</style>
