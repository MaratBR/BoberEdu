import Vue from "vue";
import {useStore} from "vuex-simple";
import CommonStore from "@common/store/CommonStore";

export default class StoreComponent<TStore extends object = CommonStore> extends Vue {
    protected store = useStore<TStore>(this.$store)
}
