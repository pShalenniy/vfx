<template>
  <div class="alternative-talents-block">
    <template v-if="!isEmpty(candidates)">
      <h4 class="h4 mb-4 mt-4">
        {{ $t('client.candidate.content_section.blocks.alternative_talent') }}
      </h4>

      <VueSlickCarousel v-bind="carouselSettings">
        <div
          v-for="candidateItem in candidates"
          :key="candidateItem.id"
          class="talent-item"
        >
          <a
            :href="route('candidate.show', { candidateSlug: candidateItem.slug })"
            class="text-primary text-condensed"
          >
            <img
              v-if="candidateItem.picture"
              :src="candidateItem.picture"
              alt=""
              height="150"
              width="150"
            />
            <img
              v-else
              :src="candidateDefaultImage"
              alt=""
              height="150"
              width="150"
            />
            <div class="talent-item__title">
              <p class="text-primary text-condensed">
                <b>{{ candidateItem.full_name }}</b>
              </p>
              <p
                v-if="!isEmpty(candidateItem.current_job_roles)"
                class="text-primary text-condensed"
              >
                {{ getMultipleItemsInString(candidateItem.current_job_roles, 'name', ', ') }}
              </p>
            </div>
          </a>
        </div>
      </VueSlickCarousel>
    </template>
  </div>
</template>

<script>
import VueSlickCarousel from 'vue-slick-carousel';
import hasMultipleItems from '@client/js/mixins/hasMultipleItems';

export default {
  components: {
    VueSlickCarousel,
  },

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
      candidates: [],
      candidateDefaultImage: Object.freeze(window[window.globalSettingsKey].candidateDefaultImage),
      carouselSettings: {
        dots: true,
        focusOnSelect: true,
        infinite: true,
        speed: 500,
        slidesToShow: 3,
        slidesToScroll: 3,
        responsive: [
          {
            breakpoint: 768,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 2,
            },
          },
          {
            breakpoint: 575,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
            },
          },
        ],
      },
    };
  },

  created() {
    this.getAlternativeTalents();
  },

  methods: {
    async getAlternativeTalents() {
      this.$overlay.show();

      try {
        const { data } = await axios.get(route('candidate.alternative-talent', this.candidate.id));

        this.candidates = data.data;
      } catch (e) {
        console.error(e);
        this.$notify.errors(e);
      } finally {
        this.$overlay.hide();
      }
    },
  },
};
</script>
