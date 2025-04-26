<template>
  <div id="next-availability-block">
    <div class="profile-grid-card background-blue text-center">
      <div class="card">
        <h1 class="h1 mb-0 text-white">
          <b>
            {{ $t('client.candidate.content_section.personal.next_availability') }}
          </b>
        </h1>
        <h2 v-if="candidate.available" class="h2 mt-2 mb-0 text-white">
          {{ candidate.available }}
        </h2>
      </div>
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
