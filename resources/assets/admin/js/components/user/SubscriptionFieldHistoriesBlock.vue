<template>
  <div
    v-if="!isEmpty(fieldHistories)"
    class="subscription-field-histories-block mt-2"
  >
    <div v-show="isExpanded">
      <p v-for="history in fieldHistories" :key="history.id">
        <slot v-bind:history="history">
          {{ history.created_at }}
          {{ $t('admin.user.field_history.has_changed') }}
          <template v-if="history.previous_value">
            {{ $t('admin.user.field_history.from') }}
            {{ history.previous_value }}
          </template>
          {{ $t('admin.user.field_history.to') }}
          {{ history.new_value }}
        </slot>
      </p>
    </div>

    <div class="mt-1">
      <button
        v-if="isExpanded"
        class="btn btn-outline-primary"
        @click="toggleExpanded"
      >
        <feather-icon
          icon="ClockIcon"
          class="text-primary"
        />
        {{ $t('admin.user.field_history.hide_history') }}
      </button>
      <button
        v-else
        class="btn btn-outline-primary"
        @click="toggleExpanded"
      >
        <feather-icon
          icon="ClockIcon"
          class="text-primary"
        />
        {{ $t('admin.user.field_history.show_history') }}
      </button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    fieldHistories: {
      type: Array,
      required: true,
    },
  },

  data() {
    return {
      isExpanded: false,
    };
  },

  methods: {
    toggleExpanded() {
      this.isExpanded = !this.isExpanded;
    },
  },
};
</script>
