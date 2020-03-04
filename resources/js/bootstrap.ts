import axios from "axios"
import {parse} from "./json";

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.transformResponse = [function (data: any, headers?: any) {
    return parse(data);
}];

