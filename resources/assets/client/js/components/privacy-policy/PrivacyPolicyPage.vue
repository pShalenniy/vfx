<template>
  <div id="privacy-policy-page">
    <div v-html="text" />
  </div>
</template>

<script>
import contentData from '@common/js/constants/contentData';

export default {
  data() {
    return {
      text: Object.freeze(
        window[window.globalSettingsKey].privacyPolicy[contentData.KEY_PAGE_PRIVACY_POLICY_TEXT],
      ),
    };
  },
};
</script>
