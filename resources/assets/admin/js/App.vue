<template>
  <div id="layout">
    <b-overlay
      :show="appStore.layout.overlay"
      rounded="sm"
      fixed
      no-wrap
      z-index="9999"
    />

    <component :is="layout">
      <router-view />
    </component>
  </div>
</template>

<script>
import { useAppStore } from '@common/js/store/app';

export default {
  data() {
    return {
      appStore: useAppStore(),
    };
  },

  computed: {
    layout() {
      if (this.$route.meta?.layout) {
        return `${this.$route.meta.layout}-layout`;
      }

      const firstRouteMatch = this.$route.matched[0];

      if (this.$route.matched.length > 1 && firstRouteMatch.meta && firstRouteMatch.meta?.layout) {
        return `${firstRouteMatch.meta.layout}-layout`;
      }

      return 'default-layout';
    },
  },
  mounted() {
    if (this.appStore.layout.skin === 'dark') {
      document.body.classList.add('dark-layout');
    }
  },
};
</script>
