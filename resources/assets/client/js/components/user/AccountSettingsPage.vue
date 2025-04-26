<template>
  <section id="account-settings-page" class="section bg-primary">
    <div class="container">
      <b-row>
        <b-col md="8" class="mx-auto">
          <account-settings-menu-block class="mb-4" />
          <b-card>
            <account-settings-block />
          </b-card>
          <b-card class="mt-4">
            <department-subscription-block />
          </b-card>
        </b-col>
      </b-row>
    </div>
  </section>
</template>

<script>
import AccountSettingsBlock from '@client/js/components/user/AccountSettingsBlock';
import AccountSettingsMenuBlock from '@client/js/components/user/AccountSettingsMenuBlock';
import DepartmentSubscriptionBlock from '@client/js/components/user/DepartmentSubscriptionBlock';

export default {
  components: {
    AccountSettingsBlock,
    AccountSettingsMenuBlock,
    DepartmentSubscriptionBlock,
  },
};
</script>
