<template>
  <div id="forgot-password-page" class="section-auth-block">
    <forgot-password-block
      forgot-password-url="forgot-password.post"
      login-url="login.view"
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
