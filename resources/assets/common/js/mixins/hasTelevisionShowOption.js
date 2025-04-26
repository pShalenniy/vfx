import debounce from 'lodash/debounce';

export default {
  data() {
    return {
      televisionShows: Object.freeze(window[window.globalSettingsKey].televisionShows),
    };
  },

  methods: {
    onSearchTelevisionShows(keyword, loading) {
      if (!keyword.length) {
        return;
      }

      loading(true);

      this.searchTelevisionShows(keyword, loading, this);
    },

    searchTelevisionShows: debounce(async (keyword, loading, vm) => {
      try {
        const { data } = await axios.get(route('common.television-show.search'), { params: { keyword } });

        vm.televisionShows = data.data;
      } catch (e) {
        vm.$notify.errors(e);
      } finally {
        loading(false);
      }
    }, 700),
  },
};
