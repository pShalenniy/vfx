<template>
  <b-modal
    id="modal-role-create"
    no-close-on-backdrop
    size="lg"
    @ok="ok"
  >
    <role-form
      ref="form"
      :role="role"
      :errors="errors"
      @submit="store"
    />
  </b-modal>
</template>

<script>
import RoleForm from '@admin/js/components/role/RoleForm';

export default {
  components: {
    RoleForm,
  },

  data() {
    return {
      role: {
        name: null,
        permissions: [],
      },
      errors: [],
    };
  },

  methods: {
    ok($event) {
      $event.preventDefault();

      this.$refs.form.submitForm();
    },

    async store(user) {
      this.$overlay.show();

      this.errors = [];

      try {
        await axios.post(route('admin.role.store'), user);

        this.$notify.success(this.$t('admin.role.action.create.success'));

        this.$bvModal.hide('modal-role-create');

        this.$emit('created');
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
