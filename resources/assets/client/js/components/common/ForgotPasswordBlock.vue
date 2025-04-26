<template>
  <div id="forgot-password-block">
    <h2 class="h2 section-title text-center text-secondary">
      {{ $t('client.forgot-password.form.title') }}
    </h2>
    <h4 class="h4 text-center text-secondary mb-3">
      {{ $t('client.forgot-password.form.subtitle') }}
    </h4>
    <b-form
      class="auth-forgot-password-form mt-2"
      @submit.prevent="sendPasswordLink"
    >
      <b-form-group :label="$t('client.forgot-password.form.email.label')">
        <b-form-input
          v-model="data.email"
          type="email"
          :placeholder="$t('client.forgot-password.form.email.placeholder')"
        />
        <small
          v-if="errors.email?.length > 0"
          class="text-danger"
        >
          {{ errors.email[0] }}
        </small>
      </b-form-group>
      <b-button
        variant="primary"
        type="submit"
      >
        {{ $t('client.forgot-password.form.submit_button.title') }}
      </b-button>
    </b-form>
    <p>
      {{ $t('client.register.form.link.label') }}
      <a :href="route(loginUrl)">
        {{ $t('client.forgot-password.form.link.login') }}
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
    forgotPasswordUrl: {
      type: String,
      required: true,
    },
  },

  data() {
    return {
      data: {
        email: null,
      },
      errors: [],
    };
  },

  methods: {
    async sendPasswordLink() {
      this.$overlay.show();

      this.errors = [];

      try {
        await axios.post(route(this.forgotPasswordUrl), this.data);

        this.$notify.success(this.$t('client.forgot-password.notification.success'));

        window.location.href = route(this.loginUrl);
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
