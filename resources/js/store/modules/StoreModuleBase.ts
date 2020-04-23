import {AxiosInstance, AxiosRequestConfig} from "axios";

export default class StoreModuleBase {
    protected client: AxiosInstance;

    constructor(client: AxiosInstance) {
        this.client = client;
    }
}
