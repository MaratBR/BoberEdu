import {ITimestamps} from "./model";

export interface Purchase extends ITimestamps {
    price: number
    status: 'failed' | 'pending'
    external_redirect_url: string,
    user_id: number
}
