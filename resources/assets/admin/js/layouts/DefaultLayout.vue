<template>
  <div
    class="page-container vertical-layout h-100"
    :class="[layoutClasses]"
    :data-col="null"
  >
    <b-navbar
      :toggleable="false"
      :variant="navbarBackgroundColor"
      class="header-navbar navbar navbar-shadow align-items-center"
      :class="[navbarTypeClass]"
    >
      <slot
        name="navbar"
        :toggle-vertical-menu-active="toggleVerticalMenuActive"
        :navbar-background-color="navbarBackgroundColor"
        :navbar-type-class="[
          ...navbarTypeClass,
          'header-navbar navbar navbar-shadow align-items-center',
        ]"
      >
        <app-navbar-vertical-layout :toggle-vertical-menu-active="toggleVerticalMenuActive" />
      </slot>
    </b-navbar>

    <vertical-nav-menu
      :nav-menu-items="navMenuItems"
      :is-vertical-menu-active="true"
      :toggle-vertical-menu-active="toggleVerticalMenuActive"
    >
      <template #header="slotProps">
        <slot
          name="vertical-menu-header"
          v-bind="slotProps"
        />
      </template>
    </vertical-nav-menu>

    <div class="sidenav-overlay" />

    <div class="app-content content with-navbar">
      <div class="content-overlay" />
      <div class="content-wrapper">
        <div class="content-body">
          <router-view />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { onUnmounted } from 'vue';
import AppNavbarVerticalLayout from '@admin/js/vendor/vuexy/app-navbar/AppNavbarVerticalLayout';
import VerticalNavMenu from '@admin/js/vendor/vuexy/vertical-nav-menu/VerticalNavMenu';
import useVerticalLayout from '@admin/js/layouts/components/useVerticalLayout';
import navigation from '@admin/js/layouts/components/navigation';
import { useAppStore } from '@common/js/store/app';

export default {
  components: {
    AppNavbarVerticalLayout,
    VerticalNavMenu,
  },

  mixins: [
    navigation,
  ],

  setup() {
    const appStore = useAppStore();

    const { navbarBackgroundColor, navbarType, footerType } = appStore.layout;

    const {
      isVerticalMenuActive,
      toggleVerticalMenuActive,
      isVerticalMenuCollapsed,
      layoutClasses,
      overlayClasses,
      resizeHandler,
      navbarTypeClass,
      footerTypeClass,
    } = useVerticalLayout(navbarType, footerType);

    resizeHandler();

    window.addEventListener('resize', resizeHandler);

    onUnmounted(() => {
      window.removeEventListener('resize', resizeHandler);
    });

    return {
      isVerticalMenuActive,
      toggleVerticalMenuActive,
      isVerticalMenuCollapsed,
      overlayClasses,
      layoutClasses,
      navbarBackgroundColor,
      navbarTypeClass,
      footerTypeClass,
    };
  },
};
</script>
