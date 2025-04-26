import { ref, computed, watch } from 'vue';
import { useAppStore } from '@common/js/store/app';

export default function useVerticalLayout(navbarType, footerType) {
  const appStore = useAppStore();

  const isVerticalMenuActive = ref(true);

  const toggleVerticalMenuActive = () => {
    isVerticalMenuActive.value = !isVerticalMenuActive.value;
  };

  const currentBreakpoint = ref('xl');

  const isVerticalMenuCollapsed = computed(() => {
    return appStore.layout.verticalMenu.isCollapsed;
  });

  const layoutClasses = computed(() => {
    const classes = [];

    if (currentBreakpoint.value === 'xl') {
      classes.push('vertical-menu-modern');
      classes.push(isVerticalMenuCollapsed.value ? 'menu-collapsed' : 'menu-expanded');
    } else {
      classes.push('vertical-overlay-menu');
      classes.push(isVerticalMenuActive.value ? 'menu-open' : 'menu-hide');
    }

    // Navbar
    if (navbarType === 'hidden' && currentBreakpoint.value !== 'xl') {
      classes.push('navbar-static');
    } else {
      classes.push(`navbar-${navbarType}`);
    }

    // Footer
    if (footerType === 'sticky') {
      classes.push('footer-fixed');
    } else if (footerType === 'static') {
      classes.push('footer-static');
    } else if (footerType === 'hidden') {
      classes.push('footer-hidden');
    }

    return classes;
  });

  const navbarBackgroundColor = computed({
    get: () => {
      return appStore.layout.navbarBackgroundColor;
    },
    set: (value) => {
      appStore.setLayoutBackgroundColor(value);
    },
  });

  // ------------------------------------------------
  // Resize handler for Breakpoint
  // ------------------------------------------------
  watch(currentBreakpoint, (value) => {
    isVerticalMenuActive.value = value === 'xl';
  });

  const resizeHandler = () => {
    if (window.innerWidth >= 1200) {
      currentBreakpoint.value = 'xl';
    } else if (window.innerWidth >= 992) {
      currentBreakpoint.value = 'lg';
    } else if (window.innerWidth >= 768) {
      currentBreakpoint.value = 'md';
    } else if (window.innerWidth >= 576) {
      currentBreakpoint.value = 'sm';
    } else {
      currentBreakpoint.value = 'xs';
    }
  };

  const overlayClasses = computed(() => {
    if (currentBreakpoint.value !== 'xl' && isVerticalMenuActive.value) {
      return 'show';
    }

    return null;
  });

  const navbarTypeClass = computed(() => {
    if (navbarType === 'sticky') {
      return 'fixed-top';
    }

    if (navbarType === 'static') {
      return 'navbar-static-top';
    }

    if (navbarType === 'hidden') {
      if (currentBreakpoint.value !== 'xl') {
        return 'navbar-static';
      }

      return 'd-none';
    }

    return 'floating-nav';
  });

  const footerTypeClass = computed(() => {
    if (footerType === 'static') {
      return 'footer-static';
    }

    if (footerType === 'hidden') {
      return 'd-none';
    }

    return '';
  });

  return {
    isVerticalMenuActive,
    toggleVerticalMenuActive,
    isVerticalMenuCollapsed,
    layoutClasses,
    overlayClasses,
    navbarBackgroundColor,
    navbarTypeClass,
    footerTypeClass,
    resizeHandler,
  };
}
