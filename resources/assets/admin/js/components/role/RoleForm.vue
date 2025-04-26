<template>
  <b-form @submit="submitForm">
    <b-row>
      <b-col cols="12" md="12">
        <b-form-group>
          <label>{{ $t('admin.role.form.name') }}</label>
          <b-input
            v-model="roleData.name"
            type="text"
            :placeholder="$t('admin.role.form.name')"
            required
          />
          <small
            v-if="errors.name?.length > 0"
            class="text-danger"
          >
            {{ errors.name[0] }}
          </small>
        </b-form-group>
      </b-col>
    </b-row>
    <b-row>
      <b-col cols="12" md="12">
        <label>{{ $t('admin.role.form.permissions') }}</label>
        <b-form-checkbox-group
          v-model="roleData.permissions"
          value-field="id"
          text-field="description"
          :options="permissions"
        />
      </b-col>
    </b-row>
  </b-form>
</template>

<script>
import cloneDeep from 'lodash/cloneDeep';

export default {
  props: {
    role: {
      type: Object,
      required: true,
    },
    errors: {
      type: Array,
      required: false,
      default() {
        return [];
      },
    },
  },

  data() {
    return {
      roleData: cloneDeep(this.role),
      permissions: window[window.globalSettingsKey].permissions,
    };
  },

  methods: {
    submitForm() {
      this.$emit('submit', Object.assign({}, this.roleData));
    },
  },
};
</script>
