import {AxiosResponse} from "axios";

export function getError(from: any) {
    console.error(from)
    return 'Unexpected error'
}
