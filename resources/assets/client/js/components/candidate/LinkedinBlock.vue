<template>
  <div class="linkedin-block">
    <h5 class="h5 text-primary">
      {{ $t('client.candidate.content_section.linkedin_info') }}
    </h5>
    <show-more-less-clamp :line-count="showMoreLineCount">
      <div v-for="experience in candidate.linkedin_experiences" :key="experience.id">
        <p v-if="experience.company" class="mt-2 mb-2">
          <b>{{ experience.company }}</b>
        </p>
        <p v-if="!isEmpty(experience.working_period)" class="mt-2 mb-4">
          {{ getLinkedinWorkingPeriod(experience.working_period) }}
        </p>
        <template v-if="experience.details?.length > 0">
          <div v-for="detail in experience.details" :key="detail.id" class="mt-2">
            <p v-if="detail.title" class="mb-2">
              {{ detail.title }}
            </p>
            <p v-if="detail.location" class="mb-2">
              {{ detail.location }}
            </p>
            <p v-if="detail.employment">
              {{ detail.employment }}
            </p>
            <p class="mb-4">
              <template v-if="detail.dates">
                {{ detail.dates }}
              </template>
            </p>
          </div>
        </template>
      </div>
    </show-more-less-clamp>
  </div>
</template>

<script>
import hasLinkedinExperience from '@client/js/mixins/hasLinkedinExperience';
import ShowMoreLessClamp from '@common/js/components/ShowMoreLess/ShowMoreLessClamp';

export default {
  components: {
    ShowMoreLessClamp,
  },

  mixins: [
    hasLinkedinExperience,
  ],

  props: {
    candidate: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      // According to design.
      showMoreLineCount: 18,
    };
  },
};
</script>
