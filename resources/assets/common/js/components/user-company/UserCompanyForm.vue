<template>
  <div id="user-company-form">
    <b-form>
      <b-form-group>
        <label>{{ $t('common.user_company.name') }}</label>
        <b-form-input
          v-model="companyItem.name"
          type="text"
          :placeholder="$t('common.user_company.name')"
          required
        />
        <small
          v-if="errors['company.name']?.length > 0"
          class="text-danger"
        >
          {{ errors['company.name'][0] }}
        </small>
      </b-form-group>
      <b-form-group>
        <label>{{ $t('common.user_company.logo.label') }}</label>
        <b-form-file
          :placeholder="$t('common.user_company.logo.placeholder')"
          :drop-placeholder="$t('common.user_company.logo.drop_placeholder')"
          :accept="getAcceptableExtensions('image')"
          @change="onFileChange($event)"
        />
        <small
          v-if="errors['company.logo']?.length > 0"
          class="text-danger"
        >
          {{ errors['company.logo'][0] }}
        </small>
      </b-form-group>
      <b-form-group>
        <label>{{ $t('common.user_company.url') }}</label>
        <b-form-input
          v-model="companyItem.url"
          type="text"
          :placeholder="$t('common.user_company.url')"
          required
        />
        <small
          v-if="errors['company.url']?.length > 0"
          class="text-danger"
        >
          {{ errors['company.url'][0] }}
        </small>
      </b-form-group>
    </b-form>
  </div>
</template>

<script>
import cloneDeep from 'lodash/cloneDeep';
import hasFileInput from '@common/js/mixins/hasFileInput';

export default {
  mixins: [
    hasFileInput,
  ],

  props: {
    company: {
      type: Object,
      required: true,
    },
    errors: {
      type: Object,
      required: false,
      default() {
        return {};
      },
    },
  },

  data() {
    return {
      companyItem: cloneDeep(this.company),
    };
  },

  watch: {
    companyItem: {
      deep: true,
      handler() {
        this.$emit('submit', this.companyItem);
      },
    },
  },

  methods: {
    onFileChange($event) {
      this.companyItem.logo = $event.target.files[0];
    },
  },
};
</script>
