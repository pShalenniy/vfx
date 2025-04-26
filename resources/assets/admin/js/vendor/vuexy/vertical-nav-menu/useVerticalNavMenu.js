import { computed, ref } from 'vue';
import { useAppStore } from '@common/js/store/app';

export default function useVerticalNavMenu(props) {
  const appStore = useAppStore();

  const isVerticalMenuCollapsed = computed({
    get: () => {
      return appStore.layout.verticalMenu.isCollapsed;
    },
    set: (value) => {
      appStore.setLayoutVerticalMenuCollapsed(value);
    },
  });

  const collapseTogglerIcon = computed(() => {
    if (props.isVerticalMenuActive) {
      return isVerticalMenuCollapsed.value ? 'unpinned' : 'pinned';
    }

    return 'close';
  });

  const isMouseHovered = ref(false);

  const updateMouseHovered = (value) => {
    isMouseHovered.value = value;
  };

  const toggleCollapsed = () => {
    isVerticalMenuCollapsed.value = !isVerticalMenuCollapsed.value;
  };

  return {
    isMouseHovered,
    isVerticalMenuCollapsed,
    collapseTogglerIcon,
    toggleCollapsed,
    updateMouseHovered,
  };
}
