export default {
  data() {
    return {
      filters: {},
      meta: {
        from: 0,
        to: 0,
        total: 0,
        perPage: 50,
        currentPage: 1,
      },
    };
  },

  watch: {
    'meta.currentPage'() {
      this.fillMeta();
    },
  },

  methods: {
    fillMeta(items = null) {
      if (null !== items) {
        this.meta.total = items ? (items.length || 0) : 0;
      }

      if (this.meta.currentPage === 1) {
        this.meta.from = this.meta.total !== 0 ? 1 : 0;
      } else {
        this.meta.from = (this.meta.perPage * this.meta.currentPage) - this.meta.perPage + 1;
      }

      this.meta.to = Math.min(this.meta.total, this.meta.from + this.meta.perPage - 1);
    },
  },
};
