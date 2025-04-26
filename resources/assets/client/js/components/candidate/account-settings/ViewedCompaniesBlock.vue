<template>
  <div class="viewed-companies-block">
    <template v-if="!isEmpty(viewedCompanies)">
      <h4 class="h4 mb-4 mt-4">
        {{ $t('client.account-settings.viewed_companies') }}
      </h4>
      <VueSlickCarousel v-bind="carouselSettings">
        <div
          v-for="viewedCompany in viewedCompanies"
          :key="viewedCompany.id"
          class="viewed-company-item"
        >
          <img
            :src="viewedCompany.logo"
            alt=""
            height="150"
            width="150"
          />
          <div class="viewed-company-item__title">
            <p class="text-primary text-condensed">
              <b>{{ viewedCompany.name }}</b>
            </p>
            <p class="text-primary text-condensed">
              <b>{{ `(${viewedCompany.url})` }}</b>
            </p>
          </div>
        </div>
      </VueSlickCarousel>
    </template>
  </div>
</template>

<script>
import VueSlickCarousel from 'vue-slick-carousel';

export default {
  components: {
    VueSlickCarousel,
  },

  props: {
    candidate: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      viewedCompanies: Object.freeze(window[window.globalSettingsKey].viewedCompanies),
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
};
</script>
