<template>
  <div id="personal-block">
    <div id="main-information" class="text-medium">
      <h4 class="h4 mb-4">
        {{ $t('client.candidate.content_section.personal.title') }}
      </h4>

      <remuneration-block :candidate="candidate" />

      <h4 class="h4 mb-3 mt-4 text-primary">
        {{ $t('client.candidate.content_section.personal.email') }}
      </h4>
      <p class="mb-4 mt-3">
        {{ candidate.email }}
      </p>
      <template v-if="candidate.phone_number">
        <h4 class="h4 mb-3 mt-4 text-primary">
          {{ $t('client.candidate.content_section.personal.phone_number') }}
        </h4>
        <p class="mb-4">
          {{ candidate.phone_number }}
        </p>
      </template>
    </div>
    <div id="social-media" class="mb-2 mt-4">
      <candidate-social-media-block :candidate="candidate" />
    </div>
  </div>
</template>

<script>
import CandidateSocialMediaBlock from '@client/js/components/common/CandidateSocialMediaBlock';
import RemunerationBlock from '@client/js/components/candidate/RemunerationBlock';

export default {
  components: {
    CandidateSocialMediaBlock,
    RemunerationBlock,
  },

  props: {
    candidate: {
      type: Object,
      required: true,
    },
  },
};
</script>
