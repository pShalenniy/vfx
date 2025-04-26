import debounce from 'lodash/debounce';

export default {
  data() {
    return {
      preferredWorkEnvironments: Object.freeze(window[window.globalSettingsKey].preferredWorkEnvironments),
    };
  },

  methods: {
    onSearchPreferredWorkEnvironments(keyword, loading) {
      if (!keyword.length) {
        return;
      }

      loading(true);

      this.searchPreferredWorkEnvironments(keyword, loading, this);
    },

    searchPreferredWorkEnvironments: debounce(async (keyword, loading, vm) => {
      try {
        const { data } = await axios.get(route('common.preferred-work-environment.search'), { params: { keyword } });

        vm.preferredWorkEnvironments = data.data;
      } catch (e) {
        vm.$notify.errors(e);
      } finally {
        loading(false);
      }
    }, 700),
  },
};
