<template>
  <b-modal
    id="modal-timezone-edit"
    no-close-on-backdrop
    size="lg"
    @ok="ok"
  >
    <disclaimer :model="editableTimezone" />

    <timezone-form
      ref="form"
      :timezone="editableTimezone"
      @submit="update"
    />
  </b-modal>
</template>

<script>
import { useAdminTimezoneStore } from '@admin/js/store/timezone';
import Disclaimer from '@admin/js/components/common/Disclaimer';
import TimezoneForm from '@admin/js/components/timezone/TimezoneForm';

export default {
  components: {
    Disclaimer,
    TimezoneForm,
  },

  props: {
    editableTimezone: {
      type: Object,
      required: true,
    },
  },

  methods: {
    ok($event) {
      $event.preventDefault();

      this.$refs.form.submitForm();
    },

    async update(timezone) {
      this.$overlay.show();

      const adminTimezoneStore = useAdminTimezoneStore();

      try {
        await adminTimezoneStore.updateTimezone(timezone);

        this.$notify.success(this.$t('admin.timezone.action.edit.success'));

        this.$bvModal.hide('modal-timezone-edit');
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
