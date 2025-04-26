<template>
  <b-modal
    id="modal-skill-edit"
    no-close-on-backdrop
    @ok="ok"
  >
    <disclaimer :model="editableSkill" />

    <skill-form
      ref="form"
      :skill="editableSkill"
      @submit="update"
    />
  </b-modal>
</template>

<script>
import Disclaimer from '@admin/js/components/common/Disclaimer';
import SkillForm from '@admin/js/components/skill/SkillForm';

export default {
  components: {
    Disclaimer,
    SkillForm,
  },

  props: {
    editableSkill: {
      type: Object,
      required: true,
    },
  },

  methods: {
    ok($event) {
      $event.preventDefault();

      this.$refs.form.submitForm();
    },

    async update(skill) {
      this.$overlay.show();

      try {
        await axios.patch(route('admin.skill.update', skill), skill);

        this.$notify.success(this.$t('admin.skill.action.edit.success'));

        this.$bvModal.hide('modal-skill-edit');

        this.$emit('updated');
      } catch (e) {
        console.error(e);
        this.$notify.errors(e);
      } finally {
        this.$overlay.hide();
      }
    },
  },
};
</script>
