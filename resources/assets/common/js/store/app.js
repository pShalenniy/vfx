import { defineStore } from 'pinia';

const appSettings = window[window.globalSettingsKey].app;

export const useAppStore = defineStore('appStore', {
  state: () => ({
    initialized: false,
    name: appSettings?.name,
    layout: {
      skin: 'dark',
      overlay: false,
      navbarBackgroundColor: '',
      navbarType: 'hidden',
      footerType: 'static',
      verticalMenu: {
        isCollapsed: false,
      },
    },
  }),

  actions: {
    showOverlay() {
      this.layout.overlay = true;
    },

    hideOverlay() {
      this.layout.overlay = false;
    },

    setLayoutBackgroundColor(value) {
      this.layout.navbarBackgroundColor = value;
    },

    setLayoutVerticalMenuCollapsed(value) {
      this.layout.verticalMenu.isCollapsed = value;
    },
  },
});
