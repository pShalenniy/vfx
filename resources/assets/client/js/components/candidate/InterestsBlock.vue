<template>
  <div id="interests-block">
    <div
      v-if="
        candidate.would_like_work_on ||
        !isEmpty(candidate.want_work_with_tools) ||
        !isEmpty(candidate.want_learn_skills) ||
        !isEmpty(candidate.desired_job_roles?.length) ||
        !isEmpty(candidate.next_promotion_job_roles) ||
        !isEmpty(candidate.preferred_sectors) ||
        !isEmpty(candidate.preferred_work_environments) ||
        !isEmpty(candidate.preferred_locations) ||
        candidate.travel_availability
      "
      class="text-medium"
    >
      <h4 class="h4 mb-3 text-light-blue">
        {{ $t('client.candidate.content_section.interests') }}
      </h4>
      <div v-if="candidate.would_like_work_on" class="mb-4">
        <p class="mb-3">
          <b>
            {{ $t('client.candidate.content_section.would_like_work_on') }}
          </b>
        </p>
        <show-more-less-clamp>
          {{ candidate.would_like_work_on }}
        </show-more-less-clamp>
      </div>
      <div v-if="candidate.want_work_with_tools?.length > 0" class="mb-4">
        <p class="mb-3">
          <b>
            {{ $t('client.candidate.content_section.professional.want_work_with_tools') }}
          </b>
        </p>
        <ul
          v-for="wantWorkWithTool in candidate.want_work_with_tools"
          :key="wantWorkWithTool.value"
        >
          <li>
            {{ `- ${wantWorkWithTool.label}` }}
          </li>
        </ul>
      </div>
      <div v-if="candidate.want_learn_skills?.length > 0" class="mb-4">
        <p class="mb-3">
          <b>
            {{ $t('client.candidate.content_section.professional.want_learn_skills') }}
          </b>
        </p>
        <ul v-for="learnSkill in candidate.want_learn_skills" :key="learnSkill.value">
          <li>
            {{ `- ${learnSkill.label}` }}
          </li>
        </ul>
      </div>

      <div v-if="candidate.desired_job_roles?.length > 0" class="mb-4">
        <p class="mb-3">
          <b>
            {{ $t('client.candidate.content_section.professional.desired_job_roles') }}
          </b>
        </p>
        <ul v-for="desiredJobRole in candidate.desired_job_roles" :key="desiredJobRole.id">
          <li>
            {{ `- ${desiredJobRole.name}` }}
          </li>
        </ul>
      </div>
      <div v-if="candidate.next_promotion_job_roles?.length > 0" class="mb-4">
        <p class="mb-3">
          <b>
            {{ $t('client.candidate.content_section.professional.next_promotion_job_roles') }}
          </b>
        </p>
        <ul
          v-for="nextPromotionJobRole in candidate.next_promotion_job_roles"
          :key="nextPromotionJobRole.id"
        >
          <li>
            {{ `- ${nextPromotionJobRole.name}` }}
          </li>
        </ul>
      </div>
      <div v-if="candidate.preferred_sectors?.length > 0" class="mb-4">
        <p class="mb-3">
          <b>
            {{ $t('client.candidate.content_section.professional.preferred_sectors') }}
          </b>
        </p>
        <ul v-for="preferred_sector in candidate.preferred_sectors" :key="preferred_sector.value">
          <li>
            {{ `- ${preferred_sector.name}` }}
          </li>
        </ul>
      </div>
      <div v-if="candidate.preferred_work_environments?.length > 0" class="mb-4">
        <p class="mb-3">
          <b>
            {{ $t('client.candidate.content_section.professional.preferred_work_environments') }}
          </b>
        </p>
        <ul
          v-for="preferredWorkEnvironments in candidate.preferred_work_environments"
          :key="preferredWorkEnvironments.id"
        >
          <li>
            {{ `- ${preferredWorkEnvironments.name}` }}
          </li>
        </ul>
      </div>
      <div v-if="candidate.preferred_locations?.length > 0" class="mb-4">
        <p class="mb-3">
          <b>
            {{ $t('client.candidate.content_section.personal.preferred_location') }}
          </b>
        </p>
        <ul
          v-for="preferredLocation in candidate.preferred_locations"
          :key="preferredLocation.id"
        >
          <li>
            {{ `- ${preferredLocation.name}` }}
          </li>
        </ul>
      </div>
      <p>
        <b>
          {{ $t('client.candidate.content_section.professional.travel_availability.title') }}
        </b>
        {{
          $t(`client.candidate.content_section.professional.travel_availability.${candidate.travel_availability ? 'yes' : 'no'}`)
        }}
      </p>
    </div>
  </div>
</template>

<script>
import ShowMoreLessClamp from '@common/js/components/ShowMoreLess/ShowMoreLessClamp';

export default {
  components: {
    ShowMoreLessClamp,
  },

  props: {
    candidate: {
      type: Object,
      required: true,
    },
  },
};
</script>
