<template>
  <div id="login-page" class="section-auth-block">
    <login-block
      :login-callback="setUser"
      forgot-password-url="forgot-password.view"
      redirect-url="candidate.page.list"
      login-url="login.post"
      email-verification-resend-url="email.verification.resend"
    >
      <template #subtitle>
        <h2 class="h3 text-center text-secondary">
          {{ $t('client.login.subtitle') }}
        </h2>
      </template>
      <template #register-button>
        <p class="mt-2">
          {{ $t('client.login.form.links.register_label') }}
          <a class="text-primary" :href="route('sign-up.view')">
            {{ $t('client.login.form.links.register') }}
          </a>
        </p>
      </template>
    </login-block>
  </div>
</template>

<script>
import { useUserStore } from '@common/js/store/user';
import LoginBlock from '@client/js/components/common/LoginBlock';

export default {
  components: {
    LoginBlock,
  },

  methods: {
    async setUser(data) {
      const userStore = useUserStore();

      await userStore.addUser(data);
    },
  },
};
</script>
