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
            let newIndex = this._new.indexOf(model);
            if (newIndex !== -1)
                this._new.splice(newIndex, 1);
            else
                this._deleted.push(model);
        }
    }

    get deleted() { return this._deleted }
    get created() { return this._new }
}
