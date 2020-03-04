import * as moment from "moment";

function reviewer(key: string, val: any): any {

    return val
}

export function parse(v: string) {
    v = v.trim();
    if (v === '')
        return '';
    return JSON.parse(v, reviewer)
}
