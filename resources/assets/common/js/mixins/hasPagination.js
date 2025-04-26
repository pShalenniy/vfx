export default {
  data() {
    return {
      getDataMethod: null,
      filters: {},
      meta: {
        from: 0,
        to: 0,
        total: 0,
        perPage: 25,
        currentPage: 1,
      },
    };
  },

  watch: {
    'meta.currentPage': {
      handler() {
        if (this.getDataMethod) {
          this[this.getDataMethod].apply();
        }
      },
    },
    filters: {
      handler() {
        if (this.getDataMethod) {
          this[this.getDataMethod].apply();
        }
      },
      deep: true,
    },
  },

  methods: {
    fillMeta(data) {
      this.meta.from = (data.meta || data).from ?? 0;
      this.meta.to = (data.meta || data).to ?? 0;
      this.meta.total = (data.meta || data).total ?? 0;
      this.meta.perPage = Number((data.meta || data).per_page) || 10;
      this.meta.currentPage = (data.meta || data).current_page || 1;
    },
  },
};
