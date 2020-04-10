const PROXY_HANDLER_KEY = '__PROXY__';


class ModelProxyHandler<T extends object> implements ProxyHandler<T> {
    public readonly inner: object = {};

    get(target: T, p: string | number | symbol, receiver: any): any {
        if (p === PROXY_HANDLER_KEY)
            return this;

        if (typeof this.inner[p] === 'undefined')
            return target[p];
        return this.inner[p];
    }

    set(target: T, p: string | number | symbol, value: any, receiver: any): boolean {
        if (value === target[p])
            delete this.inner[p];
        else
            this.inner[p] = value;

        return true;
    }
}

export function makeStagedProxy<T extends object>(target: T): T {
    return new Proxy(target, new ModelProxyHandler<T>())
}

export function getStagedChangeset<T extends object>(o: T): Partial<T> {
    let handler = (o as any)[PROXY_HANDLER_KEY];
    if (handler instanceof ModelProxyHandler)
        return handler.inner as Partial<T>;
    throw new Error("Given object is not a proxy")
}
