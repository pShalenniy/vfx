export default {
  data() {
    return {
      shortlists: window[window.globalSettingsKey].shortlists,
    };
  },

  methods: {
    async getShortlists() {
      this.$overlay.show();

      await this.getShortlistsWithoutOverlay();

      this.$overlay.hide();
    },

    async getShortlistsWithoutOverlay() {
      try {
        const { data } = await axios.get(route('shortlist.list'));

        this.shortlists = data.data;

        window[window.globalSettingsKey].shortlists = data.data;
      } catch (e) {
        console.error(e);
      }
    },
  },
};
