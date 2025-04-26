<template>
  <b-modal
    id="modal-our-partner-edit"
    no-close-on-backdrop
    @ok="ok"
  >
    <our-partner-form
      ref="form"
      :our-partner="editableOurPartner"
      @submit="update"
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

  props: {
    editableOurPartner: {
      type: Object,
      required: true,
    },
  },

  methods: {
    ok($event) {
      $event.preventDefault();

      this.$refs.form.submitForm();
    },

    async update(ourPartner) {
      this.$overlay.show();

      const file = jsonToFormData(Object.assign(
        {},
        { logo: ourPartner.logo },
        { _method: 'PATCH' },
      ));

      try {
        await axios.post(route('admin.our-partner.update', ourPartner),
          file,
          {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          },
        );

        this.$notify.success(this.$t('admin.our-partner.action.edit.success'));

        this.$bvModal.hide('modal-our-partner-edit');

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
