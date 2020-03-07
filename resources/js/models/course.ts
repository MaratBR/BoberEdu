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
        // TODO Check if this is a bad idea (why did i do this?)
        val.about = val.about || '';
        super(val);

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


    set name(v) { this.set('name', v) }
    set about(v) { this.set('about', v) }
    set price(v) { this.set('price', v) }
    set sign_up_beg(v) { this.set('sign_up_beg', v) }
    set sign_up_end(v) { this.set('sign_up_end', v) }
}
