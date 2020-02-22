/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from "axios"
import {api} from "./api";

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Authentication'] = api.getAuthentication();

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

const meta = loadMeta();

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token: HTMLMetaElement | null = document.head.querySelector('meta[name="csrf-token"]');

if (meta.csrf) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = meta.csrf
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}
