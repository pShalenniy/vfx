export default {
  methods: {
    getMultipleItemsInString(items, key = 'name', separator = ', ', text = null) {
      if (!items.length) {
        return '';
      }

      const values = items.map((item) => item[key]).join(separator);

      if (text !== null) {
        return `${text} ${values}`;
      }

      return values;
    },
  },
};
