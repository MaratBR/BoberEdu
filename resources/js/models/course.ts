import {DateStr, TimestampsModel} from "./model";

interface ICourse {
    name: string
    about: string
    price: number
    sign_up_beg: DateStr
    sign_up_end: DateStr
}

interface ICourseStatic {
    name: string
}

export default class Course extends TimestampsModel<ICourse, ICourseStatic> {
    get name(): string { return this.get('name') }
    get about(): string { return this.get('about') }
    get price(): number { return this.get('price') }
    get sign_up_beg(): DateStr { return this.get('sign_up_beg') }
    get sign_up_end(): DateStr { return this.get('sign_up_end') }


    set name(v) { this.set('name', v) }
    set about(v) { this.set('about', v) }
    set price(v) { this.set('price', v) }
    set sign_up_beg(v) { this.set('sign_up_beg', v) }
    set sign_up_end(v) { this.set('sign_up_end', v) }
}
