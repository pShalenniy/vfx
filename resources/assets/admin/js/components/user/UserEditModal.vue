<template>
  <b-modal
    id="modal-user-edit"
    no-close-on-backdrop
    size="lg"
    @ok="ok"
  >
    <user-form
      ref="form"
      :user="editableUser"
      :errors="errors"
      @submit="update"
    />
  </b-modal>
</template>

<script>
import jsonToFormData from 'json-form-data';
import UserForm from '@admin/js/components/user/UserForm';

export default {
  components: {
    UserForm,
  },

  props: {
    editableUser: {
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

    async update(user) {
      this.$overlay.show();

      this.errors = [];

      const userData = jsonToFormData(
        Object.assign(
          {},
          user,
          { _method: 'PATCH' },
        ),
      );

      try {
        await axios.post(route('admin.user.update', user.id),
          userData,
          {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          },
        );

        this.$notify.success(this.$t('admin.user.action.edit.success'));

        this.$bvModal.hide('modal-user-edit');

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
