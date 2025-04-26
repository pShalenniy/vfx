import { defineStore } from 'pinia';
import { useRoleStore } from '@common/js/store/role';
import storage from '@common/js/utilities/storage';

export const useUserStore = defineStore('userStore', {
  state: () => ({
    user: {},
    permissions: [],
  }),

  actions: {
    async addUser(data) {
      if (data.token) {
        storage.set('token', data.token);
      }

      if (data.user) {
        this.user = data.user;
      }
    },

    async deleteUser() {
      this.user = null;

      this.permissions = [];
    },

    async refreshPermissions() {
      const roleStore = useRoleStore();

      if (!this.user.role_id) {
        return false;
      }

      const rolePermissions = [];

      const role = roleStore.roles.find((item) => item.id === this.user.role_id);

      if (role) {
        rolePermissions.push(...role.permissions);
      }

      const permissions = {};

      [...new Set(rolePermissions)].forEach((permission) => {
        permissions[permission] = true;
      });

      this.permissions = permissions;
    },
  },
});
