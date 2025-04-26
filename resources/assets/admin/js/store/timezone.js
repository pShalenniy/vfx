import { defineStore } from 'pinia';
import { useCommonTimezoneStore } from '@common/js/store/timezone';

export const useAdminTimezoneStore = defineStore('adminTimezoneStore', {
  actions: {
    async addTimezone(timezone) {
      const commonTimezonesStore = useCommonTimezoneStore();

      const { data } = await axios.post(route('admin.timezone.store'), timezone);

      if (data.data) {
        commonTimezonesStore.timezones.push(data.data);

        commonTimezonesStore.timezones.sort((a, b) => {
          if (a.name.toLowerCase() < b.name.toLowerCase()) {
            return -1;
          }

          if (a.name.toLowerCase() > b.name.toLowerCase()) {
            return 1;
          }

          return 0;
        });
      }
    },
    async updateTimezone(timezone) {
      const commonTimezonesStore = useCommonTimezoneStore();

      const { data } = await axios.patch(route('admin.timezone.update', timezone), timezone);

      if (data.data) {
        const index = commonTimezonesStore.timezones.findIndex(
          (timezone) => timezone.id === data.data.id,
        );

        commonTimezonesStore.timezones.splice(index, 1, data.data);
      }
    },
    async deleteTimezone(id) {
      const commonTimezonesStore = useCommonTimezoneStore();

      const { data } = await axios.delete(route('admin.timezone.destroy', id));

      if (data.message) {
        const index = commonTimezonesStore.timezones.findIndex((timezone) => timezone.id === id);

        commonTimezonesStore.timezones.splice(index, 1);
      }
    },
  },
});
