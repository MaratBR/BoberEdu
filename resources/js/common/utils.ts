import {AxiosResponse} from "axios";

function isObject(v: any): v is { [key: string]: any } {
    return typeof v === 'object' && v !== null
}

export function getError(from: any) {
    console.error(from)

    if (isObject(from)) {
        if (isObject(from.response)) {
            return from.response.data
        }
    }

    return 'Unexpected error'
}

export type RGB = {
    r: number,
    g: number,
    b: number
}

export function hexToRgb(color: string): RGB {
    if (color.charAt(0) === '#')
        color = color.substr(1)
    return {
        r: parseInt(color.substr(0, 2), 16),
        g: parseInt(color.substr(2, 2), 16),
        b: parseInt(color.substr(4, 2), 16)
    }
}

export function getBrightness({r, g, b}: RGB): number {
    return Math.round((r * 299 + g * 587 + b * 11) / 1000)
}

