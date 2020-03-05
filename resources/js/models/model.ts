export class Model<T extends object> {
    private readonly _value: object = {};
    private _stagedChanges: object | null = null;

    protected set(key: PropertyKey, val: any) {
        if (this._stagedChanges !== null) {
            if (val === this._value[key])
                delete this._stagedChanges[key];
            else
                this._stagedChanges[key] = val;
        }
        else
            this._value[key] = val;
    }

    protected get(key: PropertyKey): any {
        if (this._stagedChanges !== null) {
            // @ts-ignore
            let val: (T & Partial<TStatic>)[K] | undefined = this._stagedChanges[key];
            if (typeof val !== 'undefined')
                return val;
        }
        return this._value[key];
    }

    enableStaging(): void {
        this._stagedChanges = this._stagedChanges || {};
    }

    getStagedChanges(): Partial<T> {
        if (this._stagedChanges === null)
            throw new Error('Staging changes is not enabled');
        return this._stagedChanges;
    }

    flushStagedChanges(): void {
        this._stagedChanges = null;
    }
}

export interface IIDModel {
    id?: number
}

export class IDModel<T extends object> extends Model<IIDModel & T>{
    get id(): number { return this.get('id') }

    get isPersistent(): boolean { return typeof this.id !== 'undefined' }

    constructor({id}: Partial<IIDModel>) {
        super();
        this.set('id', id);
    }

}

export type DateStr = string;

export interface ITimestamps extends IIDModel {
    created_at?: DateStr
}

export class TimestampsModel<T extends object> extends IDModel<T & ITimestamps>{
    get created_at(): DateStr { return this.get('created_at') }

    constructor(val: Partial<ITimestamps>) {
        super(val);
        this.set('created_at', val.created_at)
    }
}

export type ModelType<
    TData extends object,
    T extends Model<TData>> = {new(val: TData): T};

export function model<
    TData extends object,
    T extends Model<TData>
    >(model: {new(val: TData): T}, val: TData): T {
    return new model(val);
}

export function makeModel<
    TData extends object,
    T extends Model<TData>
    >(model: {new(val: TData): T}): (val: TData) => T {
    return (val: (TData) | null) => val !== null ? new model(val) : null;
}
