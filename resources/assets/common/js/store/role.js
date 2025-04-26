import { defineStore } from 'pinia';

export const useRoleStore = defineStore('roleStore', {
  state: () => ({
    roles: [],
  }),

  actions: {
    async addRoles() {
      const { data } = await axios.get(route('permission.roles'));

      if (data.data) {
        this.roles = data.data;
      }
    },
  },
});
