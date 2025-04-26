<template>
  <div id="about-us-page">
    <section class="section background-blue-light text-white">
      <div class="container">
        <h3 class="h3 mb-5 text-big">
          <b>
            {{ title }}
          </b>
        </h3>
        <div class="about-us-text text-medium" v-html="text"></div>
      </div>
    </section>
    <section class="section background-blue text-white">
      <div class="container">
        <h3 class="h3 mb-5 text-big text-primary">
          <b>
            {{ theTeamTitle }}
          </b>
        </h3>
        <about-us-item-block
          :link-title="theTeamJohnLinkTitle"
          :email="theTeamJohnEmail"
          :text="theTeamJohnText"
          :position="theTeamJohnPosition"
          :full-name="theTeamJohnFullName"
          :image="theTeamJohnImage"
        />

        <about-us-item-block
          :link-title="theTeamAllenLinkTitle"
          :email="theTeamAllenEmail"
          :text="theTeamAllenText"
          :position="theTeamAllenPosition"
          :full-name="theTeamAllenFullName"
          :image="theTeamAllenImage"
        />
      </div>
    </section>
  </div>
</template>

<script>
import contentData from '@common/js/constants/contentData';
import AboutUsItemBlock from '@client/js/components/about-us/AboutUsItemBlock';

export default {
  components: {
    AboutUsItemBlock,
  },

  data() {
    const pageData = window[window.globalSettingsKey].aboutUsPage;

    return {
      title: Object.freeze(
        pageData[contentData.KEY_PAGE_ABOUT_US_TITLE],
      ),
      text: Object.freeze(
        pageData[contentData.KEY_PAGE_ABOUT_US_TEXT],
      ),
      theTeamTitle: Object.freeze(
        pageData[contentData.KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_TITLE],
      ),

      theTeamJohnFullName: Object.freeze(
        pageData[contentData.KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_FULL_NAME],
      ),
      theTeamJohnPosition: Object.freeze(
        pageData[contentData.KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_POSITION],
      ),
      theTeamJohnImage: Object.freeze(
        pageData[contentData.KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_IMAGE],
      ),
      theTeamJohnText: Object.freeze(
        pageData[contentData.KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_TEXT],
      ),
      theTeamJohnEmail: Object.freeze(
        pageData[contentData.KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_EMAIL],
      ),
      theTeamJohnLinkTitle: Object.freeze(
        pageData[contentData.KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_LINK_TITLE],
      ),
      theTeamAllenFullName: Object.freeze(
        pageData[contentData.KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_FULL_NAME],
      ),
      theTeamAllenPosition: Object.freeze(
        pageData[contentData.KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_POSITION],
      ),
      theTeamAllenImage: Object.freeze(
        pageData[contentData.KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_IMAGE],
      ),
      theTeamAllenText: Object.freeze(
        pageData[contentData.KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_TEXT],
      ),
      theTeamAllenEmail: Object.freeze(
        pageData[contentData.KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_EMAIL],
      ),
      theTeamAllenLinkTitle: Object.freeze(
        pageData[contentData.KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_LINK_TITLE],
      ),
    };
  },
};
</script>
