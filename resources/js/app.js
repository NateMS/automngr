require('./bootstrap');

// Import modules...
import { createApp, h } from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import Unicon from 'vue-unicons';
import { createStore } from 'vuex'
import { uniPalette, uniCalendarAlt, uniPlusCircle, uniMeh, uniUsersAlt, uniCarSideview, uniDashboard, uniSearch, uniFilter, uniFilterSlash, uniTrashAlt, uniPen, uniExclamationTriangle, uniMapMarker, uniPhone, uniEnvelope, uniFileDownload, uniArrowDown, uniArrowUp, uniArrowRight, uniAngleRight, uniAngleUp, uniAngleDown, uniAngleLeft, uniFileUploadAlt } from 'vue-unicons/dist/icons'

Unicon.add([uniPalette, uniCalendarAlt, uniPlusCircle, uniMeh, uniUsersAlt, uniCarSideview, uniDashboard, uniSearch, uniFilter, uniFilterSlash, uniTrashAlt, uniPen, uniExclamationTriangle, uniMapMarker, uniPhone, uniEnvelope, uniFileDownload, uniArrowDown, uniArrowUp, uniArrowRight, uniAngleRight, uniAngleUp, uniAngleDown, uniAngleLeft, uniFileUploadAlt])

// Create a new store instance.
const store = createStore({
    state () {
        return {
            sideBarOpen: false
        }
    },
    getters: {
        sideBarOpen: state => {
            return state.sideBarOpen
        }
    },
    mutations: {
        toggleSidebar (state) {
            state.sideBarOpen = !state.sideBarOpen
        }
    },
    actions: {
        toggleSidebar(context) {
            context.commit('toggleSidebar')
        }
    }
})

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
    .use(store)
    .use(Unicon, {
        fill: '#4B5563',
        height: 32,
        width: 32
      })
    .mount(el);

InertiaProgress.init({ color: '#4B5563' });
