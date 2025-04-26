<template>
  <vue-select
    v-bind="{ ...$attrs, ...$props }"
    v-on="$listeners"
  >
    <template #no-options>
      {{ $t('common.vue_select.start_typing') }}
    </template>
  </vue-select>
</template>

<script>
import VueSelect from '@common/js/components/VSelect/VSelectBase';

export default {
  components: {
    VueSelect,
  },
};
</script>
