<template>
  <div id="terms-and-conditions-page">
    <disclaimer-modal />

    <div v-html="text" />

    <div class="text-center pb-4">
      <a
        href="#"
        class="btn btn-primary text-blue"
        @click.prevent="displayDisclaimer"
      >
        {{ $t('common.disclaimer') }}
      </a>
    </div>
  </div>
</template>

<script>
import contentData from '@common/js/constants/contentData';
import DisclaimerModal from '@client/js/components/terms-and-conditions/DisclaimerModal';

export default {
  components: {
    DisclaimerModal,
  },

  data() {
    return {
      text: Object.freeze(
        window[window.globalSettingsKey].termsAndConditions[contentData.KEY_PAGE_TERMS_AND_CONDITIONS_TEXT],
      ),
    };
  },

  methods: {
    displayDisclaimer() {
      this.$bvModal.show('modal-disclaimer');
    },
  },
};
</script>
