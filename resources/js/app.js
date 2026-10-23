// Import modules...
import { createApp, h } from 'vue';
import { createInertiaApp, Link } from '@inertiajs/vue3'
import Unicon from 'vue-unicons';
import { createStore } from 'vuex';
import {
  uniChart, uniFileAlt, uniPalette, uniCalendarAlt, uniPlusCircle, uniMeh, uniUsersAlt, uniCarSideview, uniDashboard, uniSearch, uniFilter, uniFilterSlash, uniTrashAlt, uniPen, uniExclamationTriangle, uniMapMarker, uniPhone, uniEnvelope, uniFileDownload, uniArrowDown, uniArrowUp, uniArrowRight, uniAngleRight, uniAngleUp, uniAngleDown, uniAngleLeft, uniFileUploadAlt, uniHistory, uniSetting
} from 'vue-unicons/dist/icons';

import './bootstrap';

Unicon.add([uniChart, uniFileAlt, uniPalette, uniCalendarAlt, uniPlusCircle, uniMeh, uniUsersAlt, uniCarSideview, uniDashboard, uniSearch, uniFilter, uniFilterSlash, uniTrashAlt, uniPen, uniExclamationTriangle, uniMapMarker, uniPhone, uniEnvelope, uniFileDownload, uniArrowDown, uniArrowUp, uniArrowRight, uniAngleRight, uniAngleUp, uniAngleDown, uniAngleLeft, uniFileUploadAlt, uniHistory, uniSetting]);

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

// The "Back to the application" button of error pages shown in Inertia's error dialog
// cannot navigate the top window (sandboxed iframe), so it asks us to close the dialog.
window.addEventListener('message', (event) => {
  const dialog = document.getElementById('inertia-error-dialog');
  if (event.data === 'inertia-error-dialog:close' && event.source === dialog?.querySelector('iframe')?.contentWindow) {
    dialog.close();
  }
});

const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });

createInertiaApp({
  progress: {
    color: '#4B5563',
  },
  resolve: (name) => {
    const page = pages[`./Pages/${name}.vue`];
    if (!page) throw new Error(`Page not found: ${name}`);
    return page.default ?? page;
  },
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
