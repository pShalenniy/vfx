<template>
  <div id="account-setting-personal-block">
    <template v-if="editableBlock !== 'personal'">
      <b-card
        v-if="
          null !== candidateItem.gross_annual_salary ||
          null !== candidateItem.week_rate ||
          null !== candidateItem.day_rate ||
          candidateItem.salary_rate_currency?.length > 0
        "
        class="mt-2 text-medium"
      >
        <b-row v-if="candidateItem.gross_annual_salary" class="mb-4">
          <b-col cols="12">
            <p>
              <b>
                {{ $t('client.account-settings.gross_annual_salary.label') }}
              </b>
              {{ candidateItem.gross_annual_salary }}
            </p>
          </b-col>
        </b-row>
        <b-row v-if="candidateItem.week_rate" class="mb-4">
          <b-col cols="12">
            <p>
              <b>
                {{ $t('client.account-settings.week_rate.label') }}
              </b>
              {{ candidateItem.week_rate }}
            </p>
          </b-col>
        </b-row>
        <b-row v-if="candidateItem.day_rate" class="mb-4">
          <b-col cols="12">
            <p>
              <b>
                {{ $t('client.account-settings.day_rate.label') }}
              </b>
              {{ candidateItem.day_rate }}
            </p>
          </b-col>
        </b-row>
        <b-row v-if="candidateItem.salary_rate_currency?.label">
          <b-col cols="12">
            <p>
              <b>
                {{ $t('client.account-settings.salary_rate_currency.label') }}
              </b>
              {{ candidateItem.salary_rate_currency.label }}
            </p>
          </b-col>
        </b-row>
      </b-card>
    </template>
    <template v-else>
      <b-card>
        <b-form class="form-xs">
          <b-row class="mt-4 align-items-center">
            <b-col sm="4" class="mb-2 mb-sm-0">
              <p>
                <b>
                  {{ $t('client.account-settings.gross_annual_salary.label') }}
                </b>
              </p>
            </b-col>
            <b-col sm="8">
              <b-input
                v-model="candidateItem.gross_annual_salary"
                type="number"
                :placeholder="$t('client.account-settings.gross_annual_salary.placeholder')"
                required
              />
              <small
                v-if="errors.gross_annual_salary?.length > 0"
                class="text-danger"
              >
                {{ errors.gross_annual_salary[0] }}
              </small>
            </b-col>
          </b-row>
          <b-row class="mt-4 align-items-center">
            <b-col sm="4" class="mb-2 mb-sm-0">
              <p>
                <b>
                  {{ $t('client.account-settings.week_rate.label') }}
                </b>
              </p>
            </b-col>
            <b-col sm="8">
              <b-input
                v-model="candidateItem.week_rate"
                type="number"
                :placeholder="$t('client.account-settings.week_rate.placeholder')"
                required
              />
              <small
                v-if="errors.week_rate?.length > 0"
                class="text-danger"
              >
                {{ errors.week_rate[0] }}
              </small>
            </b-col>
          </b-row>
          <b-row class="mt-4 align-items-center">
            <b-col sm="4" class="mb-2 mb-sm-0">
              <p>
                <b>
                  {{ $t('client.account-settings.day_rate.label') }}
                </b>
              </p>
            </b-col>
            <b-col sm="8">
              <b-input
                v-model="candidateItem.day_rate"
                type="number"
                :placeholder="$t('client.account-settings.day_rate.placeholder')"
                required
              />
              <small
                v-if="errors.day_rate?.length > 0"
                class="text-danger"
              >
                {{ errors.day_rate[0] }}
              </small>
            </b-col>
          </b-row>
          <b-row class="mt-4 align-items-center">
            <b-col sm="4" class="mb-2 mb-sm-0">
              <p>
                <b>
                  {{ $t('client.account-settings.salary_rate_currency.label') }}
                </b>
              </p>
            </b-col>
            <b-col sm="8">
              <v-select
                v-model="candidateItem.salary_rate_currency"
                :placeholder="$t('client.account-settings.salary_rate_currency.placeholder')"
                :options="salaryRateCurrencyValues"
              />
              <small
                v-if="errors.salary_rate_currency?.length > 0"
                class="text-danger"
              >
                {{ errors.salary_rate_currency[0] }}
              </small>
            </b-col>
          </b-row>
          <div class="mt-4 text-right">
            <button
              class="btn btn-secondary"
              @click.prevent="cancel"
            >
              {{ $t('client.account-settings.buttons.cancel') }}
            </button>
            <button
              type="submit"
              class="btn btn-primary text-blue"
              @click.prevent="submitForm"
            >
              {{ $t('client.account-settings.buttons.save') }}
            </button>
          </div>
        </b-form>
      </b-card>
    </template>
  </div>
</template>

<script>
import cloneDeep from 'lodash/cloneDeep';
import salaryRateCurrencyValues from '@common/js/constants/candidateSalaryRateCurrencyConstants';

export default {
  props: {
    candidate: {
      type: Object,
      required: true,
    },
    editableBlock: {
      type: String,
      required: false,
      default() {
        return '';
      },
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
      candidateItem: cloneDeep(this.candidate),
      salaryRateCurrencyValues: Object.values(salaryRateCurrencyValues),
    };
  },

  methods: {
    cancel() {
      this.$emit('cancel');
    },

    submitForm() {
      this.$emit('submit', { candidate: this.candidateItem, blockKey: this.editableBlock });
    },
  },
};
</script>

