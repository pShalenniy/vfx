import debounce from 'lodash/debounce';

export default {
  data() {
    return {
      preferredLocations: Object.freeze(window[window.globalSettingsKey].preferredLocations),
    };
  },

  methods: {
    onSearchPreferredLocations(keyword, loading) {
      if (!keyword.length) {
        return;
      }

      loading(true);

      this.searchPreferredLocations(keyword, loading, this);
    },

    searchPreferredLocations: debounce(async (keyword, loading, vm) => {
      try {
        const { data } = await axios.get(route('common.preferred-location.search'), { params: { keyword } });

        vm.preferredLocationValues = data.data;
      } catch (e) {
        vm.$notify.errors(e);
      } finally {
        loading(false);
      }
    }, 700),
  },
};
