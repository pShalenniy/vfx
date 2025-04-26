<template>
  <ul>
    <slot name="header" />
    <component
      :is="resolveNavItemComponent(item)"
      v-for="item in items"
      :key="item.header || item.title"
      :item="item"
    />
    <slot name="footer" />
  </ul>
</template>

<script>
import { provide, ref } from 'vue';
import { resolveVerticalNavMenuItemComponent as resolveNavItemComponent } from '@admin/js/vendor/vuexy/vertical-nav-menu/utilities';
import VerticalNavMenuHeader from '@admin/js/vendor/vuexy/vertical-nav-menu/components/vertical-nav-menu-header';
import VerticalNavMenuLink from '@admin/js/vendor/vuexy/vertical-nav-menu/components/vertical-nav-menu-link/VerticalNavMenuLink';
import VerticalNavMenuGroup from '@admin/js/vendor/vuexy/vertical-nav-menu/components/vertical-nav-menu-group/VerticalNavMenuGroup';

export default {
  components: {
    VerticalNavMenuHeader,
    VerticalNavMenuLink,
    VerticalNavMenuGroup,
  },

  props: {
    items: {
      type: Array,
      required: true,
    },
  },

  setup() {
    provide('openGroups', ref([]));

    return {
      resolveNavItemComponent,
    };
  },
};
</script>
