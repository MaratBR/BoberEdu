interface ICache {
    get<T>(key: string): T | null
    set<T>(key: string, val: T, expiration?: number): T
}

type CacheEntry = {
    val: any,
    expiration?: number
}

class SimpleCache implements ICache {
    private _storage: {[key: string]: CacheEntry} = {};
    defaultExpiration = 10 * 60;

    get<T>(key: string): T {
        let entry = this._storage[key] as CacheEntry;
        if (typeof entry === 'undefined' || (entry.expiration && entry.expiration < +new Date()))
            return null;
        return entry.val;
    }

    set<T>(key: string, val: T, expiration?: number): T {
        expiration = expiration || this.defaultExpiration;
        this._storage[key] = {
            val,
            expiration
        };
        return val
    }

}
