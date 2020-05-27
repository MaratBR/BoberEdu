import {AxiosInstance, AxiosResponse} from "axios";

export default class StoreModuleBase {
    protected client: AxiosInstance;

    constructor(client: AxiosInstance) {
        this.client = client;
    }

    protected _g<T>(response: AxiosResponse<T>): T {
        return response.data
    }
}
