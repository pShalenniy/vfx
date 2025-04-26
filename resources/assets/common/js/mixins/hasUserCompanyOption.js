import debounce from 'lodash/debounce';

export default {
  data() {
    return {
      userCompanies: [],
    };
  },

  methods: {
    onSearchUserCompanies(keyword, loading) {
      if (!keyword.length) {
        return;
      }

      loading(true);

      this.searchUserCompanies(keyword, loading, this);
    },

    searchUserCompanies: debounce(async (keyword, loading, vm) => {
      try {
        const { data } = await axios.get(
          route('common.user-company.search'),
          { params: { company: encodeURIComponent(keyword) } },
        );

        if (data.companies) {
          vm.userCompanies = data.companies;
        }
      } catch (e) {
        vm.$notify.errors(e);
      } finally {
        loading(false);
      }
    }, 700),
  },
};
