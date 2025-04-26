<template>
  <div id="picture-block">
    <img
      :src="candidate.picture ? candidate.picture : candidateDefaultPicturePath"
      alt=""
      class="candidate-picture"
      :title="
        getMultipleItemsInString(
          nationalities,
          'name',
          ' & ',
          $t('client.candidate.content_section.blocks.fields.nationality'),
        )
      "
    />
    <img class="candidate-nationality-flag" :src="nationalityFlag" alt="" />
    <div v-show="!isEmpty(nationalities)" class="profile-grid-card-rollover">
      {{
        getMultipleItemsInString(
          nationalities,
          'name',
          ' & ',
          $t('client.candidate.content_section.blocks.fields.nationality'),
        )
      }}
    </div>
  </div>
</template>

<script>
import uniqBy from 'lodash/uniqBy';
import hasMultipleItems from '@client/js/mixins/hasMultipleItems';

export default {
  mixins: [
    hasMultipleItems,
  ],

  props: {
    candidate: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      candidateDefaultPicturePath: window[window.globalSettingsKey].candidateDefaultPicturePath,
      currentFlagIndex: 0,
      flagChangeTimer: null,
    };
  },

  computed: {
    nationalities() {
      let countries = [];

      if (this.candidate.country?.id) {
        countries.push(this.candidate.country);
      }

      if (this.candidate.alternative_citizenship_residencies?.length > 0) {
        countries = countries.concat(this.candidate.alternative_citizenship_residencies);
      }

      return uniqBy(countries, 'id');
    },
    nationalityFlag() {
      return this.candidate.country_flags[this.currentFlagIndex];
    },
  },

  mounted() {
    this.startFlagChangeTimer();
  },

  beforeDestroy() {
    clearInterval(this.flagChangeTimer);
  },

  methods: {
    startFlagChangeTimer() {
      if (this.candidate.country_flags.length <= 1) {
        return;
      }

      this.flagChangeTimer = setInterval(() => {
        let index = this.currentFlagIndex + 1;

        if (index === this.candidate.country_flags.length) {
          index = 0;
        }

        this.currentFlagIndex = index;
      }, 5000);
    },
  },
};
</script>
