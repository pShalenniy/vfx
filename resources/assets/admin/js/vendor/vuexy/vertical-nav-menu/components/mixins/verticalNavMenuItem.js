import { isFunction } from 'lodash/lang';

export default {
  watch: {
    $route: {
      immediate: true,
      handler() {
        this.updateIsActive();
      },
    },
  },

  methods: {
    hasAccess(item) {
      return isFunction(item.acl) ? item.acl(this, item) : true;
    },
  },
};
