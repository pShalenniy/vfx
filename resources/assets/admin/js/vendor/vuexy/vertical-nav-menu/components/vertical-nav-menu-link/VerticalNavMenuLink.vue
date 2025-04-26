<template>
  <li
    v-if="hasAccess(item)"
    class="nav-item"
    :class="{
      active: isActive,
      disabled: item.disabled,
    }"
  >
    <b-link
      v-bind="linkProps"
      class="d-flex align-items-center"
    >
      <feather-icon :icon="item.icon || 'CircleIcon'" />
      <span class="menu-title text-truncate">{{ item.title }}</span>
      <b-badge
        v-if="item.tag !== undefined"
        pill
        :variant="item.tagVariant || 'primary'"
        class="mr-1 ml-auto"
      >
        {{ item.tag }}
      </b-badge>
    </b-link>
  </li>
</template>

<script>
import useVerticalNavMenuLink from '@admin/js/vendor/vuexy/vertical-nav-menu/components/vertical-nav-menu-link/useVerticalNavMenuLink';
import verticalNavMenuItem from '@admin/js/vendor/vuexy/vertical-nav-menu/components/mixins/verticalNavMenuItem';

export default {
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
    const { isActive, linkProps, updateIsActive } = useVerticalNavMenuLink(props.item);

    return {
      isActive,
      linkProps,
      updateIsActive,
    };
  },
};
</script>
