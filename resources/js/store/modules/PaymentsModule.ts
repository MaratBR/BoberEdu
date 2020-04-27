import StoreModuleBase from "./StoreModuleBase";
import {Action} from "vuex-simple";
import {dto} from "../dto";

export type CoursePaymentOptions = {
    gateway: string,
    data: any,
    courseId: number
};

export default class PaymentsModule extends StoreModuleBase {
    @Action()
    async pay(opts: CoursePaymentOptions): Promise<dto.PaymentDto> {
        return this.client.patch('payments/course/' + opts.courseId + '/pay', {
            gateaway: opts.gateway,
            data: opts.data
        }).then(r => r.data)
    }
}
