<template>
  <div id="remuneration-block">
    <div class="text-medium">
      <p v-if="candidate.gross_annual_salary" class="mb-4">
        <b>
          {{ $t('client.candidate.content_section.remuneration.gross_annual_salary') }}
        </b>
        {{ candidate.gross_annual_salary }}
        {{ candidate.salary_rate_currency }}
      </p>
      <p v-if="candidate.week_rate" class="mb-4">
        <b>
          {{ $t('client.candidate.content_section.remuneration.week_rate') }}
        </b>
        {{ candidate.week_rate }}
        {{ candidate.salary_rate_currency }}
      </p>
      <p v-if="candidate.day_rate" class="mb-4">
        <b>
          {{ $t('client.candidate.content_section.remuneration.day_rate') }}
        </b>
        {{ candidate.day_rate }}
        {{ candidate.salary_rate_currency }}
      </p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    candidate: {
      type: Object,
      required: true,
    },
  },
};
</script>
