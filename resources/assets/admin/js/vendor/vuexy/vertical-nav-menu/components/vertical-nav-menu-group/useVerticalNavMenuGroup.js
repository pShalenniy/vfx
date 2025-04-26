import { ref, watch, inject, computed } from 'vue';
import { isNavGroupActive } from '@admin/js/vendor/vuexy/vertical-nav-menu/utilities';
import { useAppStore } from '@common/js/store/app';

export default function useVerticalNavMenuGroup(item) {
  const appStore = useAppStore();

  const isVerticalMenuCollapsed = computed(() => {
    return appStore.layout.verticalMenu.isCollapsed;
  });

  const isMouseHovered = inject('isMouseHovered');
  const openGroups = inject('openGroups');

  const isOpen = ref(false);
  const isActive = ref(false);

  const updateGroupOpen = (value) => {
    isOpen.value = value;
  };

  const updateIsActive = () => {
    isActive.value = isNavGroupActive(item.children);
  };

  const doesHaveChild = (title) => {
    return item.children.some((child) => {
      return child.title === title;
    });
  };

  watch(isVerticalMenuCollapsed, (value) => {
    if (!isMouseHovered.value) {
      if (value) {
        isOpen.value = false;
      } else if (!value && isActive.value) {
        isOpen.value = true;
      }
    }
  });

  // Collapse menu when menu is collapsed and show on open.
  watch(isMouseHovered, (value) => {
    if (isVerticalMenuCollapsed.value) {
      isOpen.value = value && isActive.value;
    }
  });

  // Collapse other groups if one group is opened.
  watch(openGroups, (currentOpenGroups) => {
    const clickedGroup = currentOpenGroups[currentOpenGroups.length - 1];

    // If current group is not clicked group or current group is not active => Proceed with closing it.
    if (clickedGroup !== item.title && !isActive.value) {
      // If clicked group is not child of current group.
      if (!doesHaveChild(clickedGroup)) {
        isOpen.value = false;
      }
    }
  });

  watch(isOpen, (value) => {
    // If group is opened push it to the array.
    if (value) {
      openGroups.value.push(item.title);
    }
  });

  watch(isActive, (value) => {
    // If menu is collapsed and not hovered(optional) then don't open group.
    if (value) {
      if (!isVerticalMenuCollapsed.value) {
        isOpen.value = value;
      }
    } else {
      isOpen.value = value;
    }
  });

  return {
    isOpen,
    isActive,
    updateGroupOpen,
    openGroups,
    isMouseHovered,
    updateIsActive,
  };
}
