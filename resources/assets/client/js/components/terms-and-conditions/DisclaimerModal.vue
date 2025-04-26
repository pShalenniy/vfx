<template>
  <b-modal
    id="modal-disclaimer"
    no-close-on-backdrop
    size="lg"
  >
    <div v-html="text" />
  </b-modal>
</template>

<script>
import contentData from '@common/js/constants/contentData';

export default {
  data() {
    return {
      text: Object.freeze(
        window[window.globalSettingsKey].termsAndConditions[contentData.KEY_PAGE_TERMS_AND_CONDITIONS_DISCLAIMER],
      ),
    };
  },
};
</script>
