import StoreModuleBase from "./StoreModuleBase";
import {Action} from "vuex-simple";
import {Purchase} from "./CoursesModule";

export default class PurchaseModel extends StoreModuleBase {
    @Action()
    async check(id: number): Promise<Purchase> {
        return this.client.patch('purchases/' + id + '/check').then(r => r.data)
    }
}
