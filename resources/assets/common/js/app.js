import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';
import { createPinia, PiniaVuePlugin } from 'pinia';
// import VueKonva from 'vue-konva';
import Toast from 'vue-toastification';
import VueSweetalert2 from 'vue-sweetalert2';
import VueTelInput from 'vue-tel-input';
import * as Sentry from '@sentry/vue';

import route from 'ziggy';
import isEmpty from 'lodash/isEmpty';
import { Ziggy } from '@common/js/generated/routes';
import axios from '@common/js/utilities/axios';
import i18n from '@common/js/i18n';

import hasRoles from '@common/js/mixins/hasRoles';

import Confirm from '@common/js/plugins/confirm';
import Notify from '@common/js/plugins/notify';
import Overlay from '@common/js/plugins/overlay';

import VSelect from '@common/js/components/VSelect/VSelectBase.vue';
import FeatherIcon from '@common/js/vendor/FeatherIcon.vue';

const pinia = createPinia();

// Vue extenders.
Vue.use(BootstrapVue);
Vue.use(PiniaVuePlugin);
Vue.use(pinia);
// Vue.use(VueKonva);
Vue.use(VueSweetalert2);

Vue.use(Toast, {
  timeout: 6000,
  hideProgressBar: true,
  closeOnClick: false,
  closeButton: false,
  icon: false,
});

Vue.use(VueTelInput, {
  dropdownOptions: {
    showDialCodeInList: true,
    showDialCodeInSelection: true,
    showFlags: true,
    showSearchBox: true,
  },
  inputOptions: {
    styleClasses: 'form-control',
    autocomplete: 'off',
    required: true,
    showDialCode: false,
    mode: 'international',
  },
});

Vue.use(Confirm);
Vue.use(Notify);
Vue.use(Overlay);

// Vue mixins.
Vue.mixin(hasRoles);
Vue.mixin({
  methods: {
    isEmpty,
    route(name, params, absolute, config = Ziggy) {
      return route(name, params, absolute, config);
    },
  },
});

// Vue components.
Vue.component('v-select', VSelect);
Vue.component('feather-icon', FeatherIcon);

// Global objects.
window.VueBus = new Vue();
window.axios = axios;

window.route = (name, params, absolute, config = Ziggy) => {
  return route(name, params, absolute, config);
};

Ziggy.url = window[window.globalSettingsKey].app.url;
Ziggy.port = window[window.globalSettingsKey].app.port;

Sentry.init({
  Vue,
  dsn: window[window.globalSettingsKey].sentry.dsn,
  environment: window[window.globalSettingsKey].sentry.environment,
  release: window[window.globalSettingsKey].sentry.release,
  integrations: [
    new Sentry.Replay(),
  ],
  tracesSampleRate: 0.0,
  replaysSessionSampleRate: 0.0,
  replaysOnErrorSampleRate: 1.0,
  initialScope: {
    user: {
      id: window[window.globalSettingsKey].user?.id,
      email: window[window.globalSettingsKey].user?.email,
    },
  },
});

export { Vue, pinia, i18n };
