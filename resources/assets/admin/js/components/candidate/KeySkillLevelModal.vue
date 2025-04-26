<template>
  <b-modal
    id="modal-key-skill-level"
    no-close-on-backdrop
    hide-footer
  >
    <p>
      {{ $t('admin.candidate.modal.key_skill_level.text') }}
    </p>

    <b-row>
      <b-col v-for="level in keySkillsLevelValues" :key="level.value" cols="6">
        <button class="btn btn-primary" @click="setLevel(level)">
          {{ level.label }}
        </button>
      </b-col>
    </b-row>
  </b-modal>
</template>

<script>
import keySkillsLevelValues from '@common/js/constants/keySkillsLevelConstants';

export default {
  data() {
    return {
      keySkillsLevelValues: Object.values(keySkillsLevelValues),
    };
  },

  methods: {
    setLevel(level) {
      this.$bvModal.hide('modal-key-skill-level');

      this.$emit('selected', level);
    },
  },
};
</script>
