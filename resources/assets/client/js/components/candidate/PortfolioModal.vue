<template>
  <b-modal
    id="modal-candidate-portfolio"
    size="xl"
    no-close-on-backdrop
    hide-footer
  >
    <div class="d-flex justify-content-center" v-html="candidate.portfolio_url"></div>
  </b-modal>
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
