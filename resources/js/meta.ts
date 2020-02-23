type Meta = {
    [key: string]: any
}

function loadMeta(): Meta {
    let meta: Meta = {};
    document.querySelectorAll('meta[name^="backend:"]').forEach($el => {
        meta[$el.getAttribute('name')!.substr(8)] = $el.getAttribute('content')
    });

    return meta
}

export const meta = loadMeta();
