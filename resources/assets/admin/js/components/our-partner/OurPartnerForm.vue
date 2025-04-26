<template>
  <b-form @submit="submitForm">
    <b-form-group>
      <label>{{ $t('admin.our-partner.modals.label') }}</label>
      <b-form-file
        v-model="ourPartnerData.logo"
        :placeholder="$t('admin.our-partner.modals.placeholder')"
        :drop-placeholder="$t('admin.our-partner.modals.drop_placeholder')"
        :accept="getAcceptableExtensions('image')"
      />
    </b-form-group>
  </b-form>
</template>

<script>
import cloneDeep from 'lodash/cloneDeep';
import hasFileInput from '@common/js/mixins/hasFileInput';

export default {
  mixins: [
    hasFileInput,
  ],

  props: {
    ourPartner: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      ourPartnerData: cloneDeep(this.ourPartner),
    };
  },

  created() {
    this.setOurPartnerPicture();
  },

  methods: {
    submitForm() {
      if (this.ourPartnerData.logo) {
        this.$emit('submit', Object.assign({}, this.ourPartnerData));
      }
    },

    setOurPartnerPicture() {
      this.ourPartnerData.logo = null;
    },
  },
};
</script>
