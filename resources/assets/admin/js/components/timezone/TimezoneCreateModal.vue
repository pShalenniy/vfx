<template>
  <b-modal
    id="modal-timezone-create"
    no-close-on-backdrop
    size="lg"
    @ok="ok"
  >
    <timezone-form
      ref="form"
      :timezone="timezone"
      @submit="store"
    />
  </b-modal>
</template>

<script>
import { useAdminTimezoneStore } from '@admin/js/store/timezone';
import TimezoneForm from '@admin/js/components/timezone/TimezoneForm';

export default {
  components: {
    TimezoneForm,
  },

  data() {
    return {
      timezone: {
        code: null,
        name: null,
        offset: null,
      },
    };
  },

  methods: {
    ok($event) {
      $event.preventDefault();

      this.$refs.form.submitForm();
    },
    async store(timezone) {
      this.$overlay.show();

      const adminTimezoneStore = useAdminTimezoneStore();

      try {
        await adminTimezoneStore.addTimezone(timezone);

        this.$notify.success(this.$t('admin.timezone.action.create.success'));

        this.$bvModal.hide('modal-timezone-create');
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
