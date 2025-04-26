<template>
  <div id="login-block">
    <h1 class="h1 section-title text-center text-secondary">
      {{ $t('client.login.title') }}
    </h1>
    <slot name="subtitle" />
    <b-form>
      <b-form-group :label="$t('client.login.form.email.label')">
        <b-form-input
          v-model="data.email"
          type="email"
          :placeholder="$t('client.login.form.email.placeholder')"
          required
        />
        <small
          v-if="errors.email?.length > 0"
          class="text-danger"
        >
          {{ errors.email[0] }}
        </small>

        <slot name="email-verification-message">
          <small
            v-if="!isEmpty(customErrors) && customErrors.key === 'email_not_verified'"
            class="text-danger"
          >
            <span>
              {{ $t('client.login.email_verification_request.message') }}
              <a href="#" @click.prevent="requestEmailVerification(customErrors.data.hash)">
                {{ $t('client.login.email_verification_request.button') }}
              </a>
            </span>
          </small>
        </slot>
      </b-form-group>
      <b-form-group :label="$t('client.login.form.password.label')">
        <b-form-input
          v-model="data.password"
          type="password"
          :placeholder="$t('client.login.form.password.placeholder')"
          required
          confirmed
        />
        <small
          v-if="errors.password?.length > 0"
          class="text-danger"
        >
          {{ errors.password[0] }}
        </small>
      </b-form-group>
      <button
        type="submit"
        class="btn btn-primary text-blue"
        @click.prevent="login"
      >
        {{ $t('client.login.form.submit_button') }}
      </button>
    </b-form>
    <a class="text-primary" :href="route(forgotPasswordUrl)">
      {{ $t('client.login.form.links.forgot_password') }}
    </a>

    <slot name="register-button" />
  </div>
</template>

<script>
export default {
  props: {
    loginUrl: {
      type: String,
      required: true,
    },
    redirectUrl: {
      type: String,
      required: true,
    },
    forgotPasswordUrl: {
      type: String,
      required: true,
    },
    loginCallback: {
      type: Function,
      required: true,
    },
    emailVerificationResendUrl: {
      type: String,
      required: false,
      default() {
        return '';
      },
    },
  },

  data() {
    return {
      data: {
        email: null,
        password: null,
      },
      errors: [],
      customErrors: [],
    };
  },

  methods: {
    async login() {
      this.$overlay.show();

      this.errors = [];
      this.customErrors = [];

      try {
        const { data } = await axios.post(route(this.loginUrl), this.data);

        if (data) {
          this.loginCallback(data);
        }

        window.location.href = route(this.redirectUrl);

        this.$notify.success(this.$t('client.login.form.notification.success'));
      } catch (error) {
        if (error.response.data.errors) {
          this.errors = error.response.data.errors;
          console.error(error.response.data?.errors);
        } else if (error.response.data.message) {
          this.$notify.error(error.response.data.message);
          console.error(error.response.data.message);
        } else if (error.response.data.error) {
          this.customErrors = error.response.data.error;

          if (this.customErrors?.message) {
            this.$notify.error(this.customErrors.message);
          }
        }
      } finally {
        this.$overlay.hide();
      }
    },

    async requestEmailVerification(hash) {
      this.$overlay.show();

      try {
        await axios.post(route(this.emailVerificationResendUrl, hash));

        this.$notify.success(
          this.$t('client.login.email_verification_request.success'),
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
