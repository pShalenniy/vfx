<template>
  <div class="imdb-block">
    <h5 class="h5 text-primary">
      {{ $t('client.candidate.content_section.filmography') }}
    </h5>

    <show-more-less-clamp :line-count="showMoreLineCount">
      <div v-for="filmographies in candidate.filmographies" :key="filmographies.id">
        <div v-for="filmography in filmographies.filmography_episodes" :key="filmography.id">
          <b>
            <p>
              {{ filmographies.title }}
              {{ filmographies.year ? `(${filmographies.year})` : '' }}
            </p>
          </b>
          <p class="mb-4">
            {{ $t(`client.candidate.role_types.${filmographies.role_type}`) }}
          </p>
        </div>
      </div>
    </show-more-less-clamp>
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

  data() {
    return {
      // According to design.
      showMoreLineCount: 18,
    };
  },
};
</script>

