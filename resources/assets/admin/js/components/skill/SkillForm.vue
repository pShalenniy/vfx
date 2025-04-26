<template>
  <b-form @submit="submitForm">
    <b-form-group>
      <label>{{ $t('admin.skill.modals.label') }}</label>
      <b-input
        v-model="skillData.title"
        type="text"
        :placeholder="$t('admin.skill.modals.placeholder')"
        required
      />
    </b-form-group>
  </b-form>
</template>

<script>
import cloneDeep from 'lodash/cloneDeep';

export default {
  props: {
    skill: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      skillData: cloneDeep(this.skill),
    };
  },

  methods: {
    submitForm() {
      this.$emit('submit', Object.assign({}, this.skillData));
    },
  },
};
</script>
