<template>
  <b-modal
    id="modal-shortlist"
    size="xl"
    hide-footer
    no-close-on-backdrop
  >
    <template #modal-title>
      <h2 class="shortlist-modal-title">
        {{ $t('client.common.shortlist.title') }}
      </h2>
    </template>
    <shortlists-list :viewing-candidate="viewingCandidate" />
  </b-modal>
</template>

<script>
import ShortlistsList from '@client/js/components/common/ShortlistsList';

export default {
  components: {
    ShortlistsList,
  },

  props: {
    viewingCandidate: {
      type: Object,
      required: false,
      default() {
        return {};
      },
    },
  },
};
</script>
