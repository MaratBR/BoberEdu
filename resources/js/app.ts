
let $app = document.getElementById('app')
let $loader = document.createElement('div')
$loader.id = 'AppLoader'
$app.append($loader)

import(
    '@app/createApp'
    /* webpackChunkName: "create-app" */
    ).then(async m => {
    await m.init()
    $loader.remove()
    m.createApp().$mount('#app')
})
