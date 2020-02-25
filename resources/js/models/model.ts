export class Model<T extends object, TStatic extends object> {
    private readonly _value: T & TStatic;

    constructor(val: T & TStatic) {
        if (typeof val === "undefined")
            throw new Error('Initial value required');
        this._value = val
    }

    protected set<K extends keyof T>(key: K, val: T[K]) {
        // @ts-ignore
        this._value[key] = val;
    }

    protected get<K extends keyof T | keyof TStatic>(key: K): (T & TStatic)[K] {
        return this._value[key];
    }
}

interface IIDModel {
    id: number
}

class IDModel<T extends object, TStatic extends object> extends Model<T, IIDModel & TStatic>{
    get id(): number { return this.get('id') }
}

export type DateStr = string;

interface ITimestamps extends IIDModel {
    created_at: DateStr
}

export class TimestampsModel<T extends object, TStatic extends object> extends IDModel<T, ITimestamps & TStatic>{
    get created_at(): DateStr { return this.get('created_at') }
}

export function model<
    TData extends object,
    TStatic extends object,
    T extends Model<TData, TStatic>
    >(model: {new(val: TData & TStatic): T}, val: TData & TStatic): T {
    return new model(val);
}

export function makeModel<
    TData extends object,
    TStatic extends object,
    T extends Model<TData, TStatic>
    >(model: {new(val: TData & TStatic): T}): (val: TData & TStatic) => T {
    return (val: TData & TStatic) => new model(val);
}
