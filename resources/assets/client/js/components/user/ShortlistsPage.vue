<template>
  <div id="shortlist-page" class="section bg-primary">
    <div class="container">
      <account-settings-menu-block class="mb-4" />

      <b-card>
        <shortlists-list />
      </b-card>
    </div>
  </div>
</template>

<script>
import AccountSettingsMenuBlock from '@client/js/components/user/AccountSettingsMenuBlock';
import ShortlistsList from '@client/js/components/common/ShortlistsList';

export default {
  components: {
    AccountSettingsMenuBlock,
    ShortlistsList,
  },
};
</script>
