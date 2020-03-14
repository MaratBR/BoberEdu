import {IDModel, IIDModel} from "./model";

export interface IUser extends IIDModel {
    name: string
    sex: string
    roles_names: string[]
    created_at: string
}

export default class User extends IDModel<IUser> {
    constructor(user: Partial<IUser>) {
        super(user);
        ({
            name: this.name,
            sex: this.sex,
            created_at: this.created_at,
            roles_names: this.roles_names
        } = user);
    }

    get name(): string { return this.get('name') }
    set name(v) { this.set('name', v) }

    get roles_names(): string[] { return this.get('roles_names') }
    set roles_names(v) { this.set('roles_names', v) }

    get created_at() { return this.get('created_at') }
    set created_at(v) { this.set('created_at', v) }

    get sex() { return this.get('sex') }
    set sex(v) { this.set('sex', v) }
}
