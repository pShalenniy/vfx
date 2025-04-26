<template>
  <div id="general-info-block">
    <div
      v-if="
        !isEmpty(candidate.skills) ||
        !isEmpty(candidate.budget_of_biggest_show) ||
        candidate.company ||
        candidate.commercial_experience ||
        candidate.vfx_notes
      "
      class="text-medium"
    >
      <div v-if="candidate.skills?.length > 0" class="mb-4">
        <p class="mb-3">
          <b>
            {{ $t('client.candidate.content_section.professional.skills') }}
          </b>
        </p>
        <ul v-for="skill in candidate.skills" :key="skill.value">
          <li>
            {{ `- ${skill.label}` }}
          </li>
        </ul>
      </div>
      <div v-if="candidate.company" class="mb-4">
        <p class="mt-2">
          <b>
            {{ $t('client.candidate.content_section.professional.company') }}
          </b>
          {{ candidate.company.name }}
        </p>
      </div>
      <div v-if="candidate.commercial_experience" class="mb-4">
        <p class="mt-1">
          <b>
            {{ $t('client.candidate.content_section.professional.commercial_experience') }}
          </b>
          {{ candidate.commercial_experience }}
        </p>
      </div>
      <div
        v-if="candidate.television_shows?.length > 0"
        class="mt-4"
      >
        <p class="mb-3">
          <b>
            {{ $t('client.candidate.content_section.professional.television_shows') }}
          </b>
        </p>
        <ul v-for="televisionShow in candidate.television_shows" :key="televisionShow.id">
          <li>
            {{ `- ${televisionShow.name}` }}
            {{ televisionShow.skill ? ` - ${televisionShow.skill}` : '' }}
          </li>
        </ul>
      </div>
      <p v-if="candidate.budget_of_biggest_show" class="mb-3">
        <b>
          {{ $t('client.candidate.content_section.professional.budget_of_biggest_show') }}
        </b>
        {{ candidate.budget_of_biggest_show }}
      </p>
      <div v-if="candidate.vfx_notes" id="would-like-work-on" class="mb-4">
        <p class="pb-3">
          <b>
            {{ $t('client.candidate.content_section.vfx_notes') }}
          </b>
        </p>
        <show-more-less-clamp>
          {{ candidate.vfx_notes }}
        </show-more-less-clamp>
      </div>
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
