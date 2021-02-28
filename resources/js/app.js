require('./bootstrap');

require('alpinejs');

/*
|----------------------------------------------------------------
| Vue 3
|----------------------------------------------------------------
*/

import { App, plugin } from '@inertiajs/inertia-vue'
import Vue from 'vue'
import Echo from "laravel-echo"

window.Pusher = require('pusher-js');


Vue.use(plugin)

const el = document.getElementById('app')

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'anis',
    wsHost:'127.0.0.1',
    wsPort: 6001,
    forceTLS: false,
    disableStats: true,
});

if (el) {
    new Vue({
        render: h => h(App, {
            props: {
                initialPage: JSON.parse(el.dataset.page),
                resolveComponent: name => require(`./Pages/${name}`).default,
            },
        }),
    }).$mount(el)

}
