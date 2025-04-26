import { Vue, pinia, i18n } from '@common/js/app';
import DefaultLayout from '@admin/js/layouts/DefaultLayout';
import ErrorLayout from '@admin/js/layouts/ErrorLayout';

import { useUserStore } from '@common/js/store/user';
import { useCommonTimezoneStore } from '@common/js/store/timezone';
import { useRoleStore } from '@common/js/store/role';

import AppCollapse from '@admin/js/vendor/vuexy/app-collapse/AppCollapse';
import AppCollapseItem from '@admin/js/vendor/vuexy/app-collapse/AppCollapseItem';

import App from '@admin/js/App.vue';

import router from '@admin/js/router';

// Components
Vue.component('app-collapse', AppCollapse);
Vue.component('app-collapse-item', AppCollapseItem);
Vue.component('default-layout', DefaultLayout);
Vue.component('error-layout', ErrorLayout);

const app = new Vue({
  pinia,
  router,
  i18n,

  methods: {
    async init() {
      const userStore = useUserStore();
      const roleStore = useRoleStore();
      const commonTimezoneStore = useCommonTimezoneStore();

      const user = window[window.globalSettingsKey].user;

      try {
        if (user === null) {
          window.location.href = '/login';
        } else {
          await userStore.addUser({ user });

          await roleStore.addRoles();

          await userStore.refreshPermissions();

          await commonTimezoneStore.getTimezones();
        }
      } catch (e) {
        console.error(e);
      } finally {
        app.$mount('#app');
      }
    },
  },

  render(h) {
    return h(App);
  },
});

app.init();
