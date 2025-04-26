<template>
  <section id="our-partners-block" class="section bg-light">
    <div class="container">
      <div id="our-partners-block">
        <h2 class="h2 section-title text-center">
          {{ $t('common.our_partners') }}
        </h2>
        <div class="partners">
          <div
            v-for="ourPartner in ourPartners"
            :key="ourPartner.id"
            class="partners-item"
          >
            <img :src="ourPartner.logo" alt="" />
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  data() {
    return {
      ourPartners: Object.freeze(window[window.globalSettingsKey].ourPartner),
    };
  },
};
</script>
