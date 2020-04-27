import StoreModuleBase from "./StoreModuleBase";
import {Action} from "vuex-simple";

export default class PaymentsModule extends StoreModuleBase {
    @Action()
    async pay(courseId: number) {
        return this.client.patch('payments/course/' + courseId + '/pay')
    }
}
