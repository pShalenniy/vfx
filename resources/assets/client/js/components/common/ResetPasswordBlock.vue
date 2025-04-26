<template>
  <div id="reset-password-block">
    <h2 class="h2 section-title text-center text-secondary">
      {{ $t('client.reset-password.form.title') }}
    </h2>
    <b-form
      class="auth-reset-password-form mt-2"
      @submit.prevent="resetPassword"
    >
      <b-form-group :label="$t('client.reset-password.form.password.label')">
        <b-form-input
          v-model="data.password"
          class="form-control-merge"
          type="password"
          name="reset-password-new"
          :placeholder="$t('client.reset-password.form.password.placeholder')"
        />
        <small
          v-if="errors.password?.length > 0"
          class="text-danger"
        >
          {{ errors.password[0] }}
        </small>
      </b-form-group>
      <b-form-group :label="$t('client.reset-password.form.password_confirmation.label')">
        <b-form-input
          v-model="data.password_confirmation"
          class="form-control-merge"
          type="password"
          name="reset-password-confirm"
          :placeholder="$t('client.reset-password.form.password_confirmation.placeholder')"
        />
        <small
          v-if="errors.password_confirmation?.length > 0"
          class="text-danger"
        >
          {{ errors.password_confirmation[0] }}
        </small>
      </b-form-group>
      <b-button
        block
        type="submit"
        variant="primary"
      >
        {{ $t('client.reset-password.form.submit_button.title') }}
      </b-button>
    </b-form>
    <p class="text-center my-2">
      <a :href="route(loginUrl)">
        {{ $t('client.reset-password.form.link.login') }}
      </a>
    </p>
  </div>
</template>

<script>
export default {
  props: {
    loginUrl: {
      type: String,
      required: true,
    },
    resetPasswordUrl: {
      type: String,
      required: true,
    },
  },

  data() {
    return {
      data: {
        password: null,
        password_confirmation: null,
      },
      errors: [],
    };
  },

  methods: {
    async resetPassword() {
      this.$overlay.show();

      this.errors = [];

      try {
        await axios.post(
          route(this.resetPasswordUrl, {
            token: window[window.globalSettingsKey].token,
            email: window[window.globalSettingsKey].email,
          }),
          this.data,
        );

        window.location.href = route(this.loginUrl);

        this.$notify.success(this.$t('client.reset-password.notification.success'));
      } catch (error) {
        if (error.response.data.errors) {
          this.errors = error.response.data.errors;
          console.error(error.response.data.errors);
        } else if (error.response.data.message) {
          this.$notify.errors(error.response.data.message);
          console.error(error.response.data.message);
        }
      } finally {
        this.$overlay.hide();
      }
    },
  },
};
</script>
