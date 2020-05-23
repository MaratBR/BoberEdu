import Vue from "vue";
import {useStore} from "vuex-simple";
import CommonStore from "@common/store/CommonStore";

export default class StoreComponent extends Vue {
    protected store = useStore<CommonStore>(this.$store)
}
