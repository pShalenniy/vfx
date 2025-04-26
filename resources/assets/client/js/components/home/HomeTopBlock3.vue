<template>
  <div class="top-card top-card--white">
    <div class="top-card__body">
      <div class="top-card__bg bg-secondary">
        <div class="top-card__bg--text">
          town
          <img class="center" :src="image" alt="" />
        </div>
        <div class="top-card__bg--buttons">
          <button>
            <a
              :href="tinsel_town_google_play"
              target="_blank"
            >
              <img src="/images/client/icons/google-play.png" alt="" height="40px" />
            </a>
          </button>
          <button>
            <a
              :href="tinsel_town_app_store"
              target="_blank"
            >
              <img src="/images/client/icons/app-store.svg" alt="" height="40px" />
            </a>
          </button>
        </div>
      </div>
      <div class="top-card__title">
        <p>{{ title }}</p>

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
        window[window.globalSettingsKey].page[contentData.KEY_PAGE_HOME_TOP_BLOCK_3_TITLE],
      ),
      image: Object.freeze(
        window[window.globalSettingsKey].page[contentData.KEY_PAGE_HOME_TOP_BLOCK_3_IMAGE],
      ),
      text: Object.freeze(
        window[window.globalSettingsKey].page[contentData.KEY_PAGE_HOME_TOP_BLOCK_3_TEXT],
      ),
      tinsel_town_google_play: Object.freeze(
        window[window.globalSettingsKey].page[contentData.KEY_BLOCK_TINSEL_TOWN_GOOGLE_PLAY],
      ),
      tinsel_town_app_store: Object.freeze(
        window[window.globalSettingsKey].page[contentData.KEY_BLOCK_TINSEL_TOWN_APP_STORE],
      ),
    };
  },
};
</script>
