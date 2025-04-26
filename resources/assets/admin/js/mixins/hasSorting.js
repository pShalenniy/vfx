export default {
  data() {
    return {
      getDataMethod: null,
      sort: {
        by: 'id',
        isDesc: false,
        direction: 'asc',
      },
      restoringSort: false,
    };
  },

  methods: {
    restoreSort(value, shouldUpdate = false) {
      if (!shouldUpdate) {
        this.restoringSort = true;
      }

      this.$set(this, 'sort', value);

      if (!shouldUpdate) {
        this.restoringSort = false;
      }
    },
    onSortChanged(context) {
      this.sort.by = context.sortBy;
      this.sort.isDesc = context.sortDesc;
      this.sort.direction = context.sortDesc ? 'desc' : 'asc';

      if (this.getDataMethod) {
        this[this.getDataMethod].apply();
      }
    },
  },
};
