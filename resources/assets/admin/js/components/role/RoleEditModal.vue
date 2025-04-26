<template>
  <b-modal
    id="modal-role-edit"
    no-close-on-backdrop
    size="lg"
    @ok="ok"
  >
    <role-form
      ref="form"
      :role="editableRole"
      :errors="errors"
      @submit="update"
    />
  </b-modal>
</template>

<script>
import RoleForm from '@admin/js/components/role/RoleForm';

export default {
  components: {
    RoleForm,
  },

  props: {
    editableRole: {
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

    async update(role) {
      this.$overlay.show();

      this.errors = [];

      try {
        await axios.patch(route('admin.role.update', role.id), role);

        this.$notify.success(this.$t('admin.role.action.edit.success'));

        this.$bvModal.hide('modal-role-edit');

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
