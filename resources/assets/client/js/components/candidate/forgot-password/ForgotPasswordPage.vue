<template>
  <div id="candidate-forgot-password-page" class="section-auth-block">
    <forgot-password-block
      forgot-password-url="candidate.forgot-password.post"
      login-url="candidate.login.view"
    />
  </div>
</template>

<script>
import ForgotPasswordBlock from '@client/js/components/common/ForgotPasswordBlock';

export default {
  components: {
    ForgotPasswordBlock,
  },
};
</script>
