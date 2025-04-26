<template>
  <b-modal
    id="modal-our-partner-create"
    no-close-on-backdrop
    @ok="ok"
  >
    <our-partner-form
      ref="form"
      :our-partner="ourPartner"
      @submit="store"
    />
  </b-modal>
</template>

<script>
import jsonToFormData from 'json-form-data';
import OurPartnerForm from '@admin/js/components/our-partner/OurPartnerForm';

export default {
  components: {
    OurPartnerForm,
  },

  data() {
    return {
      ourPartner: {
        logo: null,
      },
    };
  },

  methods: {
    ok($event) {
      $event.preventDefault();

      this.$refs.form.submitForm();
    },

    async store(ourClient) {
      try {
        this.$overlay.show();

        const file = jsonToFormData(Object.assign({}, ourClient));
        await axios.post(route('admin.our-partner.store'), file);

        this.$notify.success(this.$t('admin.our-partner.action.create.success'));

        this.$bvModal.hide('modal-our-partner-create');

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
