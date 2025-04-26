<template>
  <div id="candidate-reset-password-page" class="section-auth-block">
    <reset-password-block
      reset-password-url="candidate.reset-password.post"
      login-url="candidate.login.view"
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
