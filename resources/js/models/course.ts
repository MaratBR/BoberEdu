import {DateStr, IIDModel, ITimestamps, TimestampsModel} from "./model";
import {IUnit, default as Unit} from "./unit";

export interface ICourse extends ITimestamps {
    name: string
    about: string
    price: number
    sign_up_beg: string | null
    sign_up_end: string | null
    units?: IUnit[]
}

export interface ICoursePaginationData extends IIDModel {
    name: string
    price: number
    sign_up_be: string
    sign_up_end: string
    units_count: number
    lessons_count: number
}

export default class Course extends TimestampsModel<ICourse> {
    constructor(val: Partial<ICourse>) {
        super(val);
        val.about = val.about || '';

        this.set('units', val.units ? val.units.map(u => new Unit(u)) : []);

        ({
            name: this.name,
            about: this.about,
            price: this.price,
            sign_up_end: this.sign_up_end,
            sign_up_beg: this.sign_up_beg
        } = val);
    }

    get name(): string { return this.get('name') }
    get about(): string { return this.get('about') }
    get price(): number { return this.get('price') }
    get sign_up_beg(): string | undefined { return this.get('sign_up_beg') }
    get sign_up_end(): string | undefined { return this.get('sign_up_end') }
    get units(): Unit[] { return this.get('units') }

    get has_preview(): boolean { return this.units ? this.units.some(u => u.is_preview) : null }
    get can_join(): boolean {
        let now = +new Date();
        let beg = +new Date(this.sign_up_beg);
        let end = +new Date(this.sign_up_end);
        return (!this.sign_up_beg || beg < now) && (!this.sign_up_end || end > now)
    }
    get will_be_available(): boolean {
        let now = +new Date();
        let beg = +new Date(this.sign_up_beg);
        return this.sign_up_beg && now < beg
    }
    get was_available(): boolean {
        let now = +new Date();
        let end = +new Date(this.sign_up_end);
        return this.sign_up_end && now > end
    }


    set name(v) { this.set('name', v) }
    set about(v) { this.set('about', v) }
    set price(v) { this.set('price', v) }
    set sign_up_beg(v) { this.set('sign_up_beg', v) }
    set sign_up_end(v) { this.set('sign_up_end', v) }
}
