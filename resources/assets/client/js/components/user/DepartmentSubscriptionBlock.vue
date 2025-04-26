<template>
  <div class="department-subscription-block">
    <b-row>
      <b-col cols="12" offset-md="12">
        <h3 class="h3 mb-2">
          {{ $t('client.user.account_settings.subscription.subscription') }}
        </h3>
      </b-col>
    </b-row>
    <template v-if="!isEmpty(subscription)">
      <div class="text-right mt-2">
        <a
          href="#"
          class="btn btn-xs btn-rounded btn-dark background-blue"
          @click.prevent="requestChange"
        >
          {{ $t('client.user.account_settings.subscription.buttons.request_change') }}
        </a>
        <template v-if="subscription.pause_count < pauseCount">
          <a
            href="#"
            class="btn btn-xs btn-rounded btn-dark background-blue"
            @click.prevent="requestPause"
          >
            {{ $t('client.user.account_settings.subscription.buttons.request_pause') }}
          </a>
        </template>
        <p v-else class="mt-2">
          {{ $t('client.user.account_settings.subscription.pause_attempts_expired') }}
        </p>
      </div>
      <div class="mt-3 mb-3">
        <p v-if="subscription.starts_at">
          <b>
            {{ $t('client.user.account_settings.subscription.start_date') }}
            {{ subscription.starts_at }}
          </b>
        </p>
        <p v-if="subscription.ends_at" class="mt-2">
          <b>
            {{ $t('client.user.account_settings.subscription.end_date') }}
            {{ subscription.ends_at }}
          </b>
        </p>
      </div>
      <h5 class="h5 mb-3 mt-3">
        <b>
          {{ $t('client.user.account_settings.subscription.departments') }}
        </b>
      </h5>
      <hr class="mt-2 mb-2" />
      <div v-for="department in subscription.departments" :key="department.id">
        <p>
          <b>
            {{ department.name }}
          </b>
        </p>
        <hr class="mt-2 mb-2" />
      </div>
    </template>
    <template v-else>
      <h2 class="mt-3">
        {{ $t('client.user.account_settings.subscription.no_subscription') }}
      </h2>
    </template>
  </div>
</template>

<script>
export default {
  data() {
    return {
      pauseCount: Object.freeze(window[window.globalSettingsKey].subscriptionPauseCount),
      subscription: Object.freeze(window[window.globalSettingsKey].subscription),
    };
  },

  methods: {
    async requestChange() {
      const result = await this.$confirm.confirm({
        title: '',
        text: this.$t('client.user.account_settings.subscription.confirm.request_change'),
        icon: '',
        confirmButtonText: this.$t('common.confirmation.delete_candidate_from_shortlist.confirm_button'),
        cancelButtonText: this.$t('common.confirmation.cancel'),
        confirmButtonColor: '#DBAC35',
        cancelButtonColor: '#062E52',
        customClass: null,
      });

      if (!result.isConfirmed) {
        return;
      }

      this.$overlay.show();

      try {
        await axios.post(route('subscription.request-change'));

        this.$notify.success(
          this.$t(
            'client.user.account_settings.subscription.notification.success.request_change',
          ),
        );
      } catch (e) {
        console.error(e);
        this.$notify.errors(e);
      } finally {
        this.$overlay.hide();
      }
    },

    async requestPause() {
      const result = await this.$confirm.confirm({
        title: '',
        text: this.$t('client.user.account_settings.subscription.confirm.request_pause'),
        icon: '',
        confirmButtonText: this.$t('common.confirmation.delete_candidate_from_shortlist.confirm_button'),
        cancelButtonText: this.$t('common.confirmation.cancel'),
        confirmButtonColor: '#DBAC35',
        cancelButtonColor: '#062E52',
        customClass: null,
      });

      if (!result.isConfirmed) {
        return;
      }

      this.$overlay.show();

      try {
        await axios.post(route('subscription.request-pause'));

        this.$notify.success(
          this.$t(
            'client.user.account_settings.subscription.notification.success.request_pause',
          ),
        );
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
