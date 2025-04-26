<template>
  <div id="portfolio-block">
    <div v-show="null !== candidate[field]" v-html="candidate[field]"></div>
  </div>
</template>

<script>
export default {
  props: {
    candidate: {
      type: Object,
      required: true,
    },
    field: {
      type: String,
      required: true,
    },
  },
};
</script>
