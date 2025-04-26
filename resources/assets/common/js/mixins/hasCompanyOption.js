import debounce from 'lodash/debounce';

export default {
  data() {
    return {
      companies: Object.freeze(window[window.globalSettingsKey].companies),
    };
  },

  methods: {
    onSearchCompanies(keyword, loading) {
      if (!keyword.length) {
        return;
      }

      loading(true);

      this.searchCompanies(keyword, loading, this);
    },

    searchCompanies: debounce(async (keyword, loading, vm) => {
      try {
        const { data } = await axios.get(route('common.company.search'), { params: { keyword } });

        vm.companies = data.data;
      } catch (e) {
        vm.$notify.errors(e);
      } finally {
        loading(false);
      }
    }, 700),
  },
};
