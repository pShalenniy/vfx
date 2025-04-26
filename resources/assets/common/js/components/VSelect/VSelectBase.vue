<script>
import VueSelect from 'vue-select';
import isObject from 'lodash/isObject';

export default {
  components: {
    VueSelect,
  },

  extends: VueSelect,

  computed: {
    isValueEmpty() {
      return this.selectedValue.length === 0 || (!this.multiple && this.isEmptyObject(this.selectedValue[0]));
    },

    selectedValue() {
      let value = this.value;

      if (this.isTrackingValues) {
        value = this.$data._value;
      }

      if (!this.multiple && this.isEmptyObject(value)) {
        return [];
      }

      if (value !== undefined && value !== null && value !== '') {
        return [].concat(value);
      }

      return [];
    },
  },

  methods: {
    isEmptyObject(value) {
      if (!isObject(value)) {
        return false;
      }

      return Object.keys(value).filter((key) => value[key]).length === 0;
    },
  },
};
</script>
