<template>
  <div id="inactive-page" class="d-flex justify-content-center">
    <b-card class="mt-10">
      <h3>
        {{ text }}
      </h3>
    </b-card>
  </div>
</template>

<script>
import contentData from '@common/js/constants/contentData';

export default {
  data() {
    return {
      text: Object.freeze(
        window[window.globalSettingsKey].text[contentData.KEY_PAGE_SUBSCRIPTION_INACTIVE_BLOCK_TEXT],
      ),
    };
  },
};
</script>
