import { Vue, pinia, i18n } from '@common/js/app';
import { useCommonTimezoneStore } from '@common/js/store/timezone';
import { useAppStore } from '@common/js/store/app';
import { useRoleStore } from '@common/js/store/role';
import { useUserStore } from '@common/js/store/user';

require('@client/js/components');
require('@client/js/theme');

const app = new Vue({
  pinia,
  i18n,

  data() {
    return {
      appStore: useAppStore(),
    };
  },

  methods: {
    async init() {
      const userStore = useUserStore();
      const roleStore = useRoleStore();
      const commonTimezoneStore = useCommonTimezoneStore();

      const user = window[window.globalSettingsKey].user;

      try {
        if (user?.email_verified_at) {
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
});

app.init();
