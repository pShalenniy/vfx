<template>
  <div id="user-company-block">
    <label>
      {{ $t('common.user_company.title') }}
    </label>
    <b-form-checkbox
      v-model="toggleCreateCompany"
      switch
      size="lg"
    >
      <p v-if="toggleCreateCompany">
        {{ $t(`common.user_company.${isEdit ? 'update' : 'create'}`) }}
      </p>
      <p v-else>
        {{ $t('common.user_company.select_from_list') }}
      </p>
    </b-form-checkbox>
    <template v-if="!toggleCreateCompany">
      <v-select
        v-model="listCompany"
        class="mb-2"
        label="name"
        data-name="name"
        debounce="700"
        :options="userCompanies"
        :placeholder="$t('admin.user.form.company')"
        @search="onSearchUserCompanies"
      >
        <template #option="option">
          <b-row>
            <b-col cols="2">
              <img
                v-if="option.logo"
                :src="option.logo"
                alt=""
                height="50"
                width="50"
              />
            </b-col>
            <b-col cols="4">
              {{ option.name }}
              {{ `(${option.url})` }}
            </b-col>
          </b-row>
        </template>

        <template #no-options="{ search, searching }">
          <template v-if="searching">
            {{ $t('common.vue_select.no_results_found') }} <em>{{ search }}</em>
          </template>
          <em v-else style="opacity: 0.5;">{{ $t('common.vue_select.start_typing') }}</em>
        </template>
      </v-select>
      <small
        v-if="errors.company?.length > 0"
        class="text-danger"
      >
        {{ errors.company[0] }}
      </small>
    </template>
    <template v-else>
      <user-company-form
        :company="formCompany"
        :errors="errors"
        @submit="setFormCompany"
      />
    </template>
  </div>
</template>

<script>
import cloneDeep from 'lodash/cloneDeep';
import isEqual from 'lodash/isEqual';
import hasUserCompanyOption from '@common/js/mixins/hasUserCompanyOption';
import UserCompanyForm from '@common/js/components/user-company/UserCompanyForm';

export default {
  components: {
    UserCompanyForm,
  },

  mixins: [
    hasUserCompanyOption,
  ],

  props: {
    user: {
      type: Object,
      required: true,
    },
    isEdit: {
      type: Boolean,
      default() {
        return true;
      },
    },
    errors: {
      type: [Array, Object],
      required: false,
      default() {
        return {};
      },
    },
  },

  data() {
    const companyModel = {
      id: null,
      name: null,
      logo: null,
      url: null,
    };

    return {
      toggleCreateCompany: false,
      company: cloneDeep(this.user.company),
      listCompany: cloneDeep(this.user.company?.id ? this.user.company : companyModel),
      formCompany: cloneDeep(this.user.company?.id ? this.user.company : companyModel),
    };
  },

  watch: {
    toggleCreateCompany(value) {
      this.company = cloneDeep(value ? this.formCompany : this.listCompany);
    },
    company: {
      deep: true,
      handler(value, oldValue) {
        if (!isEqual(value, oldValue)) {
          this.$emit('submit', value);
        }
      },
    },
    listCompany: {
      deep: true,
      handler(value) {
        this.company = cloneDeep(value);
      },
    },
    formCompany: {
      deep: true,
      handler(value) {
        this.company = cloneDeep(value);
      },
    },
  },

  methods: {
    setFormCompany(company) {
      this.formCompany = company;
    },
  },
};
</script>
