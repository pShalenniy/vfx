import { useAppStore } from '@common/js/store/app';

export default (vm) => {
  const appStore = useAppStore();

  if (!vm.$overlay) {
    const $overlay = {
      show() {
        appStore.showOverlay();
      },
      hide() {
        appStore.hideOverlay();
      },
    };

    vm.$overlay = $overlay;
    vm.prototype.$overlay = $overlay;
  }
};
