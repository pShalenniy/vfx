<template>
  <div class="disclaimer">
    <p v-if="model.tinsel_town_id">
      {{ $t('admin.common.change_action_warning') }}
    </p>
  </div>
</template>

<script>
export default {
  props: {
    model: {
      type: Object,
      required: true,
    },
  },
};
</script>
