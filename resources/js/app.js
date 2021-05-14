require('./bootstrap');

// Import modules...
import { createApp, h } from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import Unicon from 'vue-unicons';
import { uniMeh, uniUsersAlt, uniCarSideview, uniDashboard, uniSearch, uniFilter, uniFilterSlash, uniTrashAlt, uniPen, uniExclamationTriangle, uniMapMarker, uniPhone, uniEnvelope, uniFileDownload, uniArrowDown, uniArrowUp, uniArrowRight, uniAngleRight, uniAngleUp, uniAngleDown, uniAngleLeft, uniFileUploadAlt } from 'vue-unicons/dist/icons'

Unicon.add([uniMeh, uniUsersAlt, uniCarSideview, uniDashboard, uniSearch, uniFilter, uniFilterSlash, uniTrashAlt, uniPen, uniExclamationTriangle, uniMapMarker, uniPhone, uniEnvelope, uniFileDownload, uniArrowDown, uniArrowUp, uniArrowRight, uniAngleRight, uniAngleUp, uniAngleDown, uniAngleLeft, uniFileUploadAlt])


const el = document.getElementById('app');

createApp({
    render: () =>
        h(InertiaApp, {
            initialPage: JSON.parse(el.dataset.page),
            resolveComponent: (name) => require(`./Pages/${name}`).default,
        }),
})
    .mixin({ methods: { route } })
    .use(InertiaPlugin)
    .use(Unicon, {
        fill: '#4B5563',
        height: 32,
        width: 32
      })
    .mount(el);

InertiaProgress.init({ color: '#4B5563' });
