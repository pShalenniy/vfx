<template>
  <div class="star-block">
    <img class="center" src="/images/client/icons/star.svg" alt="" />
    <div class="h2 mb-0 text-secondary">
      <div>
        {{ text }}
        <hr />
        <div v-show="isExpanded" class="mb-4">
          {{ expandedText }}
        </div>
      </div>
      <button v-if="isExpanded" class="btn btn-link" @click="toggleExpanded">
        {{ $t('common.show_less') }}
      </button>
      <button v-else class="btn btn-link" @click="toggleExpanded">
        {{ $t('common.show_more') }}
      </button>
    </div>
  </div>
</template>

<script>
import contentData from '@common/js/constants/contentData';

export default {
  data() {
    return {
      isExpanded: false,
      text: Object.freeze(
        window[window.globalSettingsKey].page[contentData.KEY_PAGE_HOME_BLOCK_INTRO_TEXT],
      ),
      expandedText: Object.freeze(
        window[window.globalSettingsKey].page[contentData.KEY_PAGE_HOME_BLOCK_INTRO_EXPANDED_TEXT],
      ),
    };
  },

  methods: {
    toggleExpanded() {
      this.isExpanded = !this.isExpanded;
    },
  },
};
</script>
