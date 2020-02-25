interface ModelController<T> {
    commitChanges(): void
    getStaged(): Partial<T>
}

class InnerStorage<T extends object> implements ModelController<T> {
    private readonly object: T;
    private staged: Partial<T>;
    private controller: ModelController<T>;

    constructor(o: T) {
        this.object = o;
        this.staged = {};
    }

    get(p: string | number | symbol): any {
        return this.staged[p] || this.object[p];
    }

    set(p: string | number | symbol, value: any): boolean {
        this.staged[p] = value;
        return true
    }

    has(p: string | number | symbol): boolean {
        return p in this.staged || p in this.staged;
    }

    commitChanges(): void {
        Object.assign(this.object, this.staged);
        this.staged = {};
    }

    getStaged(): Partial<T> {
        return this.staged;
    }
}

function getInnerStorage<T extends object>(o: T): InnerStorage<T> {
    if (typeof (o as any).__INNER__ === 'undefined')
        (o as any).__INNER__ = new InnerStorage(o);
    return (o as any).__INNER__ as InnerStorage<T>;
}



class ModelHandler implements ProxyHandler<object> {
    get(target: object, p: string | number | symbol, receiver: any): any {
        let inner = getInnerStorage(target);
        if (typeof inner[p] === 'function')
            return inner[p].bind(inner);
        return inner.get(p);
    }

    set(target: object, p: string | number | symbol, value: any, receiver: any): boolean {
        return getInnerStorage(target).set(p, value)
    }

    has(target: object, p: string | number | symbol): boolean {
        return getInnerStorage(target).has(p)
    }
}

export type Model<T extends object> = T & ModelController<T>;

export function makeModel<T extends object>(val: T): Model<T> {
    return new Proxy(val, new ModelHandler() as ProxyHandler<T>) as Model<T>;
}
