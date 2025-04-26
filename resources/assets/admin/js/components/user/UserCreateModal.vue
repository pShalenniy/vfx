<template>
  <b-modal
    id="modal-user-create"
    no-close-on-backdrop
    size="lg"
    @ok="ok"
  >
    <user-form
      ref="form"
      :user="userData"
      :errors="errors"
      @submit="store"
    />
  </b-modal>
</template>

<script>
import jsonToFormData from 'json-form-data';
import subscriptionPeriodValues from '@admin/js/constants/subscriptionPeriodConstants';
import subscriptionStatusValues from '@admin/js/constants/subscriptionStatusConstants';
import UserForm from '@admin/js/components/user/UserForm';

export default {
  components: {
    UserForm,
  },

  data() {
    return {
      subscriptionPeriodValues: Object.values(subscriptionPeriodValues),
      subscriptionStatusValues: Object.values(subscriptionStatusValues),
      userData: {
        first_name: null,
        last_name: null,
        email: null,
        company: {},
        country: {},
        region: {},
        city: {},
        job_title: null,
        preferred_job_roles: [],
        phone_number: null,
        has_signatory: false,
        password: null,
        password_confirmation: null,
        role_id: null,
        notify_user: false,
        is_verified: true,
        subscription: {
          status: {
            value: subscriptionStatusValues.STATUS_PENDING_DEMO.value,
            label: subscriptionStatusValues.STATUS_PENDING_DEMO.label,
          },
          seats: 1,
          period: subscriptionPeriodValues.PERIOD_THREE_MONTH.value,
          contract_signed: false,
          departments: [],
        },
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

      const userData = jsonToFormData(
        Object.assign(
          {},
          user,
        ),
      );

      try {
        await axios.post(route('admin.user.store'),
          userData,
          {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          },
        );

        this.$notify.success(this.$t('admin.user.action.create.success'));

        this.$bvModal.hide('modal-user-create');

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
