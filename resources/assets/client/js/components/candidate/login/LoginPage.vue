<template>
  <div id="candidate-login-page" class="section-auth-block">
    <login-block
      :login-callback="setCandidateToken"
      forgot-password-url="candidate.forgot-password.view"
      redirect-url="candidate.account-settings.show"
      login-url="candidate.login.post"
    />
  </div>
</template>

<script>
import storage from '@common/js/utilities/storage';
import LoginBlock from '@client/js/components/common/LoginBlock';

export default {
  components: {
    LoginBlock,
  },

  methods: {
    setCandidateToken(data) {
      if (data.token) {
        storage.set('token', data.token);
      }
    },
  },
};
</script>
