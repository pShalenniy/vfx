import { defineStore } from 'pinia';

export const useCommonTimezoneStore = defineStore('commonTimezonesStore', {
  state: () => ({
    timezones: [],
  }),

  actions: {
    async getTimezones() {
      const { data } = await axios.get(route('common.timezone.list'));

      if (data.data) {
        this.timezones = data.data;
      }
    },
  },
});
