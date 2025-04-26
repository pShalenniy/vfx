import debounce from 'lodash/debounce';

export default {
  data() {
    return {
      preferredJobRoles: Object.freeze(window[window.globalSettingsKey].jobRoles),
      currentJobRoles: Object.freeze(window[window.globalSettingsKey].jobRoles),
      desiredJobRoles: Object.freeze(window[window.globalSettingsKey].jobRoles),
      nextPromotionJobRole: Object.freeze(window[window.globalSettingsKey].jobRoles),
    };
  },

  methods: {
    onSearchJobRoles(keyword, loading, key) {
      if (!keyword.length) {
        return;
      }

      loading(true);

      this.searchJobRoles(keyword, loading, key, this);
    },

    searchJobRoles: debounce(async (keyword, loading, key, vm) => {
      try {
        const { data } = await axios.get(route('common.job-role.search'), { params: { keyword } });

        vm[key] = data.data;
      } catch (e) {
        vm.$notify.errors(e);
      } finally {
        loading(false);
      }
    }, 700),

    preparePreferredJobRole(userData) {
      if (userData.preferred_job_roles?.length > 0) {
        userData.preferred_job_roles = userData.preferred_job_roles.map((item) => {
          if (item?.id) {
            return { id: Number(item.id), name: item.name };
          }

          return { id: null, name: item };
        });
      }

      return userData;
    },
  },
};
