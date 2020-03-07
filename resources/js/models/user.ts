import {IDModel, IIDModel} from "./model";

export interface IUser extends IIDModel {
    name: string
}

export default class User extends IDModel<IUser> {
    get name() { return this.get('name') }
    set name(v) { this.set('name', v) }
}
