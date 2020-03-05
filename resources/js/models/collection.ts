import {IDModel} from "./model";

export default class ModelCollection<T extends IDModel<TData>, TData extends object> {
    private readonly inner: T[];
    private _deleted: T[] = [];
    private _new: T[] = [];

    constructor(arr: T[]) {
        this.inner = arr;
    }

    add(model: T) {
        this.inner.push(model);
        if (!model.isPersistent)
            this._new.push(model);
    }

    delete(model: T) {
        let index = this.inner.indexOf(model);
        if (index !== -1) {
            this.inner.splice(index, 1);
            this._deleted.push(model);
        } else if ((index = this._new.indexOf(model)) !== -1) {
            delete this._new[index];
        }
    }

    get deleted() { return this._deleted }
    get created() { return this._new }
}
