
let $app = document.getElementById('app')
let $loader = document.createElement('div')
$loader.id = 'AppLoader'
$app.append($loader)

import('@app/createApp').then(async m => {
    await m.init()
    $loader.remove()
    m.createApp().$mount('#app')
})
