<template>
  <b-form @submit="submitForm">
    <b-row>
      <b-col cols="12" md="12">
        <b-form-group>
          <label>{{ $t('admin.timezone.form.code.label') }}</label>
          <v-select
            v-model="timezoneData.code"
            :placeholder="$t('admin.timezone.form.code.placeholder')"
            :options="timezones.codes"
          />
        </b-form-group>
      </b-col>
    </b-row>
    <b-row>
      <b-col cols="6" md="6">
        <b-form-group>
          <label>{{ $t('admin.timezone.form.name.label') }}</label>
          <b-input
            v-model="timezoneData.name"
            type="text"
            :placeholder="$t('admin.timezone.form.name.placeholder')"
          />
        </b-form-group>
      </b-col>
      <b-col cols="6" md="6">
        <b-form-group>
          <label>{{ $t('admin.timezone.form.offset.label') }}</label>
          <v-select
            v-model="timezoneData.offset"
            :placeholder="$t('admin.timezone.form.offset.placeholder')"
            :options="timezones.offsets"
          />
        </b-form-group>
      </b-col>
    </b-row>
  </b-form>
</template>

<script>
import cloneDeep from 'lodash/cloneDeep';

export default {
  props: {
    timezone: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      timezoneData: cloneDeep(this.timezone),
      timezones: window[window.globalSettingsKey].timezonesList,
    };
  },

  methods: {
    submitForm() {
      this.$emit('submit', Object.assign({}, this.timezoneData));
    },
  },
};
</script>
