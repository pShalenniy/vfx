<template>
  <div
    class="main-menu menu-fixed menu-accordion menu-shadow"
    :class="[
      { expanded: !isVerticalMenuCollapsed || (isVerticalMenuCollapsed && isMouseHovered) },
      skin === 'semi-dark' ? 'menu-dark' : 'menu-light',
    ]"
    @mouseenter="updateMouseHovered(true)"
    @mouseleave="updateMouseHovered(false)"
  >
    <!-- main menu header-->
    <div class="navbar-header expanded">
      <slot
        name="header"
        :toggle-vertical-menu-active="toggleVerticalMenuActive"
        :toggle-collapsed="toggleCollapsed"
        :collapse-toggler-icon="collapseTogglerIcon"
      >
        <ul class="nav navbar-nav flex-row">
          <li
            v-b-tooltip.hover.bottom="$t('common.toggle_sidebar')"
            class="nav-item nav-toggle"
          >
            <b-link class="nav-link modern-nav-toggle">
              <feather-icon
                icon="XIcon"
                size="20"
                class="d-block d-xl-none"
                @click="toggleVerticalMenuActive"
              />
              <feather-icon
                :icon="collapseTogglerIconFeather"
                size="20"
                class="d-none d-xl-block collapse-toggle-icon"
                @click="toggleCollapsed"
              />
            </b-link>
          </li>
        </ul>
      </slot>
    </div>

    <!-- Shadow -->
    <div
      :class="{ 'd-block': shallShadowBottom }"
      class="shadow-bottom"
    />

    <!-- main menu content-->
    <vue-perfect-scrollbar
      :settings="perfectScrollbarSettings"
      class="main-menu-content scroll-area"
      tagname="ul"
      @ps-scroll-y="
        (event) => {
          shallShadowBottom = event.srcElement.scrollTop > 0;
        }
      "
    >
      <vertical-nav-menu-items
        :items="navMenuItems"
        class="navigation navigation-main"
      >
        <template #footer>
          <li class="nav-item">
            <a href="#" class="d-flex align-items-center" @click.prevent="logout">
              <feather-icon icon="LogOutIcon" />{{ $t('admin.navigation.logout') }}
            </a>
          </li>
        </template>
      </vertical-nav-menu-items>
    </vue-perfect-scrollbar>
  </div>
</template>

<script>
import { provide, computed, ref } from 'vue';
import VuePerfectScrollbar from 'vue-perfect-scrollbar';
import { useUserStore } from '@common/js/store/user';
import storage from '@common/js/utilities/storage';
import storageConstants from '@common/js/constants/storageConstants';
import useVerticalNavMenu from '@admin/js/vendor/vuexy/vertical-nav-menu/useVerticalNavMenu';
import VerticalNavMenuItems from '@admin/js/vendor/vuexy/vertical-nav-menu/components/vertical-nav-menu-items/VerticalNavMenuItems';

export default {
  components: {
    VuePerfectScrollbar,
    VerticalNavMenuItems,
  },

  props: {
    isVerticalMenuActive: {
      type: Boolean,
      required: true,
    },
    toggleVerticalMenuActive: {
      type: Function,
      required: true,
    },
    navMenuItems: {
      type: Array,
      required: true,
    },
  },

  setup(props) {
    const {
      isMouseHovered,
      isVerticalMenuCollapsed,
      collapseTogglerIcon,
      toggleCollapsed,
      updateMouseHovered,
    } = useVerticalNavMenu(props);

    // Shadow bottom is UI specific and can be removed by user => It's not in `useVerticalNavMenu`
    const shallShadowBottom = ref(false);

    provide('isMouseHovered', isMouseHovered);

    const perfectScrollbarSettings = {
      maxScrollbarLength: 60,
      wheelPropagation: false,
    };

    const appName = window[window.globalSettingsKey].app.name;
    const appLogo = window[window.globalSettingsKey].app.logo;
    const skin = window[window.globalSettingsKey].app.skin;

    const collapseTogglerIconFeather = computed(() => {
      return collapseTogglerIcon.value === 'unpinned' ? 'CircleIcon' : 'DiscIcon';
    });

    return {
      perfectScrollbarSettings,
      isVerticalMenuCollapsed,
      collapseTogglerIcon,
      toggleCollapsed,
      isMouseHovered,
      updateMouseHovered,
      collapseTogglerIconFeather,
      shallShadowBottom,
      skin,
      appName,
      appLogo,
    };
  },

  methods: {
    async logout() {
      this.$overlay.show();
      const userStore = useUserStore();

      try {
        const { data } = await axios.post(route('common.logout'));

        if (data) {
          await userStore.deleteUser();

          storage.remove(storageConstants.API_TOKEN);
        }

        window.location.href = route('login.view');

        this.$notify.success(this.$t('auth.logout.message.success'));
      } catch (e) {
        console.error(e);
        this.$notify.errors(e);
      } finally {
        this.$overlay.hide();
      }
    },
  },
};
</script>
