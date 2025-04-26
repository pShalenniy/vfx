<template>
  <div id="account-settings-menu">
    <div class="account-settings-menu">
      <b-nav card align="center">
        <b-nav-item
          v-for="link in links"
          :key="link.route"
          :href="route(link.route)"
          :active="isActive(link.route)"
          link-classes="btn btn-xs btn-rounded btn-outline-dark mr-2"
          active-class="btn-dark text-white"
        >
          {{ link.title }}
        </b-nav-item>
      </b-nav>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      links: [
        { route: 'user.show', title: this.$t('client.user.account_settings.menu.account_settings') },
        { route: 'user.shortlists', title: this.$t('client.user.account_settings.menu.my_shortlists') },
      ],
    };
  },

  methods: {
    isActive(url) {
      return route().current(url);
    },
  },
};
</script>
