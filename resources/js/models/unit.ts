import {ITimestamps, TimestampsModel} from "./model";

export interface IUnit extends ITimestamps {
    name: string
    about: string
    is_preview: boolean
    order_num: number
    course_id: number
}

export class Unit extends TimestampsModel<IUnit> {
    constructor(val: IUnit) {
        super(val);

        ({
            name: this.name,
            about: this.about,
            is_preview: this.is_preview,
            order_num: this.order_num,
            course_id: this.course_id,
        } = val);
    }

    get name(): string { return this.get('name') }
    get about(): string { return this.get('about') }
    get is_preview(): boolean { return this.get('is_preview') }
    get order_num(): number { return this.get('order_num') }
    get course_id(): number { return this.get('course_id') }

    set name(v) { this.set('name', v) }
    set about(v) { this.set('about', v) }
    set is_preview(v) { this.set('is_preview', v) }
    set order_num(v) { this.set('order_num', v) }
    set course_id(v) { this.set('course_id', v) }
}
