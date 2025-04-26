<template>
  <b-modal
    id="modal-candidate-edit"
    no-close-on-backdrop
    size="xl"
    @ok="ok"
  >
    <disclaimer :model="editableCandidate" />

    <candidate-form
      ref="form"
      :candidate="editableCandidate"
      :errors="errors"
      @submit="update"
    />
  </b-modal>
</template>

<script>
import jsonToFormData from 'json-form-data';
import CandidateForm from '@admin/js/components/candidate/CandidateForm';
import Disclaimer from '@admin/js/components/common/Disclaimer';

export default {
  components: {
    CandidateForm,
    Disclaimer,
  },

  props: {
    editableCandidate: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      errors: [],
    };
  },

  methods: {
    ok($event) {
      $event.preventDefault();

      this.$refs.form.submitForm();
    },

    async update(candidate) {
      this.$overlay.show();

      this.errors = [];

      const data = jsonToFormData(Object.assign({}, candidate, { _method: 'PATCH' }));

      try {
        await axios.post(route('admin.candidate.update', candidate.id),
          data,
          {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          },
        );

        this.$notify.success(this.$t('admin.candidate.action.edit.success'));

        this.$bvModal.hide('modal-candidate-edit');

        this.$emit('updated');
      } catch (error) {
        if (error.response.data.errors) {
          this.errors = error.response.data.errors;
          console.error(error.response.data.errors);
        } else if (error.response.data.message) {
          this.$notify.errors(error.response.data.message);
          console.error(error.response.data.message);
        }
      } finally {
        this.$overlay.hide();
      }
    },
  },
};
</script>
