// Import modules...
import { createApp, h } from 'vue';
import { createInertiaApp, Link } from '@inertiajs/vue3'
import Unicon from 'vue-unicons';
import { createStore } from 'vuex';
import {
  uniChart, uniFileAlt, uniPalette, uniCalendarAlt, uniPlusCircle, uniMeh, uniUsersAlt, uniCarSideview, uniDashboard, uniSearch, uniFilter, uniFilterSlash, uniTrashAlt, uniPen, uniExclamationTriangle, uniMapMarker, uniPhone, uniEnvelope, uniFileDownload, uniArrowDown, uniArrowUp, uniArrowRight, uniAngleRight, uniAngleUp, uniAngleDown, uniAngleLeft, uniFileUploadAlt,
} from 'vue-unicons/dist/icons';

require('./bootstrap');

Unicon.add([uniChart, uniFileAlt, uniPalette, uniCalendarAlt, uniPlusCircle, uniMeh, uniUsersAlt, uniCarSideview, uniDashboard, uniSearch, uniFilter, uniFilterSlash, uniTrashAlt, uniPen, uniExclamationTriangle, uniMapMarker, uniPhone, uniEnvelope, uniFileDownload, uniArrowDown, uniArrowUp, uniArrowRight, uniAngleRight, uniAngleUp, uniAngleDown, uniAngleLeft, uniFileUploadAlt]);

// Create a new store instance.
const store = createStore({
  state() {
    return {
      sideBarOpen: false,
    };
  },
  getters: {
    sideBarOpen: (state) => state.sideBarOpen,
  },
  mutations: {
    toggleSidebar(state) {
      state.sideBarOpen = !state.sideBarOpen;
    },
  },
  actions: {
    toggleSidebar(context) {
      context.commit('toggleSidebar');
    },
  },
});

const el = document.getElementById('app');

createInertiaApp({
  progress: {
    color: '#4B5563',
  },
  resolve: (name) => require(`./Pages/${name}.vue`),
  setup({ el, App, props, plugin }) {
    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(store)
      .component('inertia-link', Link)
      .use(Unicon, {
        fill: '#4B5563',
        height: 32,
        width: 32,
      })
      .mixin({ methods: { route } })
      .mount(el)      
  },
})