<template>
  <li
    v-if="hasAccess(item)"
    class="nav-item has-sub"
    :class="{
      open: isOpen,
      disabled: item.disabled,
      'sidebar-group-active': isActive,
    }"
  >
    <b-link
      class="d-flex align-items-center"
      @click="() => updateGroupOpen(!isOpen)"
    >
      <feather-icon :icon="item.icon || 'CircleIcon'" />
      <span class="menu-title text-truncate">{{ item.title }}</span>
      <b-badge
        v-if="item.tag"
        pill
        :variant="item.tagVariant || 'primary'"
        class="mr-1 ml-auto"
      >
        {{ item.tag }}
      </b-badge>
    </b-link>
    <b-collapse
      v-model="isOpen"
      class="menu-content"
      tag="ul"
    >
      <component
        :is="resolveNavItemComponent(child)"
        v-for="child in item.children"
        :key="child.header || child.title"
        ref="groupChild"
        :item="child"
      />
    </b-collapse>
  </li>
</template>

<script>
import { resolveVerticalNavMenuItemComponent as resolveNavItemComponent } from '@admin/js/vendor/vuexy/vertical-nav-menu/utilities';

import VerticalNavMenuHeader from '@admin/js/vendor/vuexy/vertical-nav-menu/components/vertical-nav-menu-header';
import VerticalNavMenuLink from '@admin/js/vendor/vuexy/vertical-nav-menu/components/vertical-nav-menu-link/VerticalNavMenuLink';

// Composition Function
import useVerticalNavMenuGroup from '@admin/js/vendor/vuexy/vertical-nav-menu/components/vertical-nav-menu-group/useVerticalNavMenuGroup';
import verticalNavMenuItem from '@admin/js/vendor/vuexy/vertical-nav-menu/components/mixins/verticalNavMenuItem';

export default {
  name: 'VerticalNavMenuGroup',

  components: {
    VerticalNavMenuHeader,
    VerticalNavMenuLink,
  },

  mixins: [
    verticalNavMenuItem,
  ],

  props: {
    item: {
      type: Object,
      required: true,
    },
  },

  setup(props) {
    const {
      isOpen,
      isActive,
      updateGroupOpen,
      updateIsActive,
    } = useVerticalNavMenuGroup(props.item);

    return {
      resolveNavItemComponent,
      isOpen,
      isActive,
      updateGroupOpen,
      updateIsActive,
    };
  },
};
</script>
