<template>
  <div id="candidate-social-media-block">
    <div id="social-media" class="text-medium">
      <a
        v-if="candidate.instagram_link"
        class="ml-2"
        :href="candidate.instagram_link"
        target="_blank"
      >
        <img src="/images/client/icons/instagram.svg" alt="" />
      </a>
      <a
        v-if="candidate.linkedin_link"
        class="ml-2"
        :href="candidate.linkedin_link"
        target="_blank"
      >
        <img src="/images/client/icons/linkedin.svg" alt="" />
      </a>
      <a
        v-if="candidate.twitter_link"
        class="ml-2"
        :href="candidate.twitter_link"
        target="_blank"
      >
        <img src="/images/client/icons/twitter.svg" alt="" />
      </a>
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
