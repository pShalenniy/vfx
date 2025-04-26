<template>
  <b-modal
    id="modal-skill-create"
    no-close-on-backdrop
    @ok="ok"
  >
    <skill-form
      ref="form"
      :skill="skill"
      @submit="store"
    />
  </b-modal>
</template>

<script>
import SkillForm from '@admin/js/components/skill/SkillForm';

export default {
  components: {
    SkillForm,
  },

  data() {
    return {
      skill: {
        title: null,
      },
    };
  },

  methods: {
    ok($event) {
      $event.preventDefault();

      this.$refs.form.submitForm();
    },

    async store(skill) {
      this.$overlay.show();

      try {
        await axios.post(route('admin.skill.store'), skill);

        this.$notify.success(this.$t('admin.skill.action.create.success'));

        this.$bvModal.hide('modal-skill-create');

        this.$emit('created');
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
