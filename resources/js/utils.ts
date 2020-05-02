export function reportError(err: any) {
    console.warn(err)
}

export function throttle<T extends any[], R>(func: (...args: T) => R, ms): (...args: T) => R {

    let isThrottled = false,
        savedArgs,
        savedThis;

    function wrapper(...args: T): R {

        if (isThrottled) {
            savedArgs = args;
            savedThis = this;
            return;
        }

        let val = func.apply(this, arguments);

        isThrottled = true;

        setTimeout(function() {
            isThrottled = false; // (3)
            if (savedArgs) {
                wrapper.apply(savedThis, savedArgs);
                savedArgs = savedThis = null;
            }
        }, ms);

        return val;
    }

    return wrapper;
}
