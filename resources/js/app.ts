let $app = document.getElementById('app')
let $loader = document.createElement('div')
$loader.id = 'AppLoader'
$app.append($loader)

import('@app/createApp').then(m => {
    let app = m.default;
    $loader.remove()
    app.$mount('#app')
})
