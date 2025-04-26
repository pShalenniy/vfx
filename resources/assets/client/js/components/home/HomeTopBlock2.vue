<template>
  <div class="top-card top-card--blue text-white">
    <div class="top-card__body">
      <div class="top-card__bg bg-black">
        <img class="top-card__bg--image" :src="image" alt="" />
        <div class="top-card__bg--text">
          <a :href="route('candidate.page.list')">
            {{ $t('common.search_link') }}
          </a>
          <img src="/images/client/icons/stars.svg" alt="" />
        </div>
      </div>
      <div class="top-card__title">
        {{ title }}

        <show-expanded-text :text="text" />
      </div>
    </div>
  </div>
</template>

<script>
import contentData from '@common/js/constants/contentData';
import ShowExpandedText from '@client/js/components/home/ShowExpandedText';

export default {
  components: {
    ShowExpandedText,
  },

  data() {
    return {
      title: Object.freeze(
        window[window.globalSettingsKey].page[contentData.KEY_PAGE_HOME_TOP_BLOCK_2_TITLE],
      ),
      image: Object.freeze(
        window[window.globalSettingsKey].page[contentData.KEY_PAGE_HOME_TOP_BLOCK_2_IMAGE],
      ),
      text: Object.freeze(
        window[window.globalSettingsKey].page[contentData.KEY_PAGE_HOME_TOP_BLOCK_2_TEXT],
      ),
    };
  },
};
</script>
