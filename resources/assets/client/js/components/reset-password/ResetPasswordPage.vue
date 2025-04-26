<template>
  <div id="reset-password-page" class="section-auth-block">
    <reset-password-block
      reset-password-url="reset-password.post"
      login-url="login.view"
    />
  </div>
</template>

<script>
import ResetPasswordBlock from '@client/js/components/common/ResetPasswordBlock';

export default {
  components: {
    ResetPasswordBlock,
  },
};
</script>
