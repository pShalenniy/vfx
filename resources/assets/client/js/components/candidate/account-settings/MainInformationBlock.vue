<template>
  <div id="account-setting-main-information-block">
    <template v-if="editableBlock !== 'main_information'">
      <b-row>
        <b-col sm="4" class="mb-4 mb-sm-0">
          <div class="candidate-profile__image">
            <img v-if="candidate.picture" :src="candidate.picture" alt="" />
            <img v-else :src="candidateDefaultPicturePath" alt="" height="170" width="170" />
          </div>
        </b-col>
        <b-col sm="8" class="mt-2 text-medium">
          <b-row class="mb-4">
            <b-col cols="6">
              <p class="mb-1">
                <b>{{ $t('client.account-settings.first_name.label') }}</b>
              </p>
            </b-col>
            <b-col cols="6">
              <p>
                {{ candidateItem.first_name }}
              </p>
            </b-col>
          </b-row>
          <b-row class="mb-4">
            <b-col cols="6">
              <p class="mb-1">
                <b>{{ $t('client.account-settings.last_name.label') }}</b>
              </p>
            </b-col>
            <b-col cols="6">
              <p>
                {{ candidateItem.last_name }}
              </p>
            </b-col>
          </b-row>
          <b-row v-if="candidateItem.current_job_roles?.length > 0" class="mb-4">
            <b-col cols="6">
              <p class="mb-1">
                <b>{{ $t('client.account-settings.current_job_roles.label') }}</b>
              </p>
            </b-col>
            <b-col cols="6">
              <p>
                {{ getMultipleItemsInString(candidateItem.current_job_roles) }}
              </p>
            </b-col>
          </b-row>
          <b-row v-if="candidateItem.nationalities?.length > 0" class="mb-4">
            <b-col cols="6">
              <p class="mb-1">
                <b>{{ $t('client.account-settings.nationalities.label') }}</b>
              </p>
            </b-col>
            <b-col cols="6">
              <p>
                {{ getMultipleItemsInString(candidateItem.nationalities) }}
              </p>
            </b-col>
          </b-row>
          <b-row v-if="candidateItem.alternative_citizenship_residencies?.length > 0" class="mb-4">
            <b-col cols="6">
              <p class="mb-1">
                <b>{{ $t('client.account-settings.alternative_citizenship_residencies.label') }}</b>
              </p>
            </b-col>
            <b-col cols="6">
              <p>
                {{ getMultipleItemsInString(candidateItem.alternative_citizenship_residencies) }}
              </p>
            </b-col>
          </b-row>
          <b-row v-if="!isEmpty(candidateItem.timezone)" class="mb-4">
            <b-col cols="6">
              <p class="mb-1">
                <b>{{ $t('client.account-settings.timezone.label') }}</b>
              </p>
            </b-col>
            <b-col cols="6">
              <p>
                {{ candidateItem.timezone.name }} - {{ candidateItem.timezone.offset }}
              </p>
            </b-col>
          </b-row>
          <b-row v-if="candidateItem.country?.name" class="mb-4">
            <b-col cols="6">
              <p class="mb-1">
                <b>{{ $t('client.account-settings.country.label') }}</b>
              </p>
            </b-col>
            <b-col cols="6">
              <p>
                {{ candidateItem.country.name }}
              </p>
            </b-col>
          </b-row>
          <b-row v-if="candidateItem.region?.name" class="mb-4">
            <b-col cols="6">
              <p class="mb-1">
                <b>{{ $t('client.account-settings.region.label') }}</b>
              </p>
            </b-col>
            <b-col cols="6">
              <p>
                {{ candidateItem.region.name }}
              </p>
            </b-col>
          </b-row>
          <b-row v-if="candidateItem.city?.name" class="mb-4">
            <b-col cols="6">
              <p class="mb-1">
                <b>{{ $t('client.account-settings.city.label') }}</b>
              </p>
            </b-col>
            <b-col cols="6">
              <p>
                {{ candidateItem.city.name }}
              </p>
            </b-col>
          </b-row>
          <b-row v-if="candidateItem.next_availability" class="mb-4">
            <b-col cols="6">
              <p class="mb-1">
                <b>{{ $t('client.account-settings.next_availability.label') }}</b>
              </p>
            </b-col>
            <b-col cols="6">
              <p>
                {{ candidateItem.next_availability }}
              </p>
            </b-col>
          </b-row>
          <b-row v-if="candidateItem.portfolio_url" class="mb-4">
            <b-col cols="6">
              <p class="mb-1">
                <b>{{ $t('client.account-settings.portfolio_url.label') }}</b>
              </p>
            </b-col>
            <b-col cols="6">
              <a :href="candidateItem.portfolio_url" target="_blank">
                {{ candidateItem.portfolio_url }}
              </a>
            </b-col>
          </b-row>
          <b-row v-if="candidateItem.shortfilm_url" class="mb-4">
            <b-col cols="6">
              <p class="mb-1">
                <b>{{ $t('client.account-settings.shortfilm_url.label') }}</b>
              </p>
            </b-col>
            <b-col cols="6">
              <a :href="candidateItem.shortfilm_url" target="_blank">
                {{ candidateItem.shortfilm_url }}
              </a>
            </b-col>
          </b-row>
        </b-col>
      </b-row>
    </template>
    <template v-else>
      <b-card class="form-xs">
        <b-row class="mt-4 align-items-center">
          <b-col sm="4" class="mb-2 mb-sm-0">
            <p>
              <b>{{ $t('client.account-settings.picture.label') }}</b>
            </p>
          </b-col>
          <b-col sm="8">
            <b-form-file
              :placeholder="$t('client.account-settings.picture.placeholder')"
              :drop-placeholder="$t('client.account-settings.picture.drop_placeholder')"
              :accept="getAcceptableExtensions('image')"
              @change="onFileChange($event)"
            />
            <small
              v-if="errors.picture?.length > 0"
              class="text-danger"
            >
              {{ errors.picture[0] }}
            </small>
          </b-col>
        </b-row>
        <b-row class="mt-4 align-items-center">
          <b-col sm="4" class="mb-2 mb-sm-0">
            <p><b>{{ $t('client.account-settings.first_name.label') }}</b></p>
          </b-col>
          <b-col sm="8">
            <b-input
              v-model="candidateItem.first_name"
              type="text"
              :placeholder="$t('client.account-settings.first_name.placeholder')"
              required
            />
            <small
              v-if="errors.first_name?.length > 0"
              class="text-danger"
            >
              {{ errors.first_name[0] }}
            </small>
          </b-col>
        </b-row>
        <b-row class="mt-4 align-items-center">
          <b-col sm="4" class="mb-2 mb-sm-0">
            <p><b>{{ $t('client.account-settings.last_name.label') }}</b></p>
          </b-col>
          <b-col sm="8">
            <b-input
              v-model="candidateItem.last_name"
              type="text"
              :placeholder="$t('client.account-settings.last_name.placeholder')"
              required
            />
            <small
              v-if="errors.last_name?.length > 0"
              class="text-danger"
            >
              {{ errors.last_name[0] }}
            </small>
          </b-col>
        </b-row>
        <b-row class="mt-4 align-items-center">
          <b-col sm="4" class="mb-2 mb-sm-0">
            <p>
              <b>{{ $t('client.account-settings.current_job_roles.label') }}</b>
            </p>
          </b-col>
          <b-col sm="8">
            <v-select
              v-model="candidateItem.current_job_roles"
              :placeholder="$t('client.account-settings.current_job_roles.placeholder')"
              :options="currentJobRoles"
              label="name"
              value="id"
              multiple
              :close-on-select="false"
              @search="(keyword, loading) => onSearchJobRoles(keyword, loading, 'currentJobRole')"
            />
            <small
              v-if="errors.current_job_roles?.length > 0"
              class="text-danger"
            >
              {{ errors.current_job_roles[0] }}
            </small>
          </b-col>
        </b-row>
        <b-row class="mt-4 align-items-center">
          <b-col sm="4" class="mb-2 mb-sm-0">
            <p>
              <b>{{ $t('client.account-settings.nationalities.label') }}</b>
            </p>
          </b-col>
          <b-col sm="8">
            <v-select
              v-model="candidateItem.nationalities"
              :placeholder="$t('client.account-settings.nationalities.placeholder')"
              label="name"
              value="id"
              :options="countryValues"
              multiple
              :close-on-select="false"
            />
            <small
              v-if="errors.nationalities?.length > 0"
              class="text-danger"
            >
              {{ errors.nationalities[0] }}
            </small>
          </b-col>
        </b-row>
        <b-row class="mt-4 align-items-center">
          <b-col sm="4" class="mb-2 mb-sm-0">
            <p>
              <b>{{ $t('client.account-settings.alternative_citizenship_residencies.label') }}</b>
            </p>
          </b-col>
          <b-col sm="8">
            <v-select
              v-model="candidateItem.alternative_citizenship_residencies"
              :placeholder="$t('client.account-settings.alternative_citizenship_residencies.placeholder')"
              :options="countryValues"
              label="name"
              value="id"
              multiple
              :close-on-select="false"
            />
            <small
              v-if="errors.alternative_citizenship_residencies?.length > 0"
              class="text-danger"
            >
              {{ errors.alternative_citizenship_residencies[0] }}
            </small>
          </b-col>
        </b-row>
        <b-row class="mt-4 align-items-center">
          <b-col sm="4" class="mb-2 mb-sm-0">
            <p>
              <b>{{ $t('client.account-settings.timezone.label') }}</b>
            </p>
          </b-col>
          <b-col sm="8">
            <v-select
              v-model="candidateItem.timezone"
              :placeholder="$t('client.account-settings.timezone.placeholder')"
              label="name"
              value="id"
              :options="timezoneValues"
            />
            <small
              v-if="errors.timezone?.length > 0"
              class="text-danger"
            >
              {{ errors.timezone[0] }}
            </small>
          </b-col>
        </b-row>
        <b-row class="mt-4 align-items-center">
          <b-col sm="4" class="mb-2 mb-sm-0">
            <p>
              <b>{{ $t('client.account-settings.country.label') }}</b>
            </p>
          </b-col>
          <b-col sm="8">
            <v-select
              v-model="candidateItem.country"
              :placeholder="$t('client.account-settings.country.placeholder')"
              label="name"
              value="id"
              :options="countryValues"
            />
            <small
              v-if="errors.country?.length > 0"
              class="text-danger"
            >
              {{ errors.country[0] }}
            </small>
          </b-col>
        </b-row>
        <b-row class="mt-4 align-items-center">
          <b-col sm="4" class="mb-2 mb-sm-0">
            <p>
              <b>{{ $t('client.account-settings.region.label') }}</b>
            </p>
          </b-col>
          <b-col sm="8">
            <v-select
              v-model="candidateItem.region"
              :placeholder="$t('client.account-settings.region.placeholder')"
              :disabled="isEmpty(candidateItem.country) || regions.length < 1"
              label="name"
              value="id"
              :options="regions"
              @search="onSearchRegions"
            />
            <small
              v-if="errors.region?.length > 0"
              class="text-danger"
            >
              {{ errors.region[0] }}
            </small>
          </b-col>
        </b-row>
        <b-row class="mt-4 align-items-center">
          <b-col sm="4" class="mb-2 mb-sm-0">
            <p>
              <b>{{ $t('client.account-settings.city.label') }}</b>
            </p>
          </b-col>
          <b-col sm="8">
            <v-select
              v-model="candidateItem.city"
              :disabled="isEmpty(candidateItem.region) || cities.length < 1"
              :placeholder="$t('client.account-settings.city.placeholder')"
              label="name"
              value="id"
              :options="cities"
              @search="onSearchCities"
            />
            <small
              v-if="errors.city?.length > 0"
              class="text-danger"
            >
              {{ errors.city[0] }}
            </small>
          </b-col>
        </b-row>
        <b-row class="mt-4 align-items-center">
          <b-col sm="4" class="mb-2 mb-sm-0">
            <p>
              <b>{{ $t('client.account-settings.next_availability.label') }}</b>
            </p>
          </b-col>
          <b-col sm="8">
            <b-form-datepicker
              v-model="candidateItem.next_availability"
              :date-format-options="calendarOptions.dateFormatOptions"
              :start-weekday="calendarOptions.startWeekday"
              :locale="calendarOptions.locale"
              :placeholder="$t('client.account-settings.next_availability.placeholder')"
            />
            <small
              v-if="errors.next_availability?.length > 0"
              class="text-danger"
            >
              {{ errors.next_availability[0] }}
            </small>
          </b-col>
        </b-row>
        <b-row class="mt-4 align-items-center">
          <b-col sm="4" class="mb-2 mb-sm-0">
            <p>
              <b>{{ $t('client.account-settings.portfolio_url.label') }}</b>
            </p>
          </b-col>
          <b-col sm="8">
            <b-input
              v-model="candidateItem.portfolio_url"
              type="url"
              :placeholder="$t('client.account-settings.portfolio_url.placeholder')"
            />
            <small
              v-if="errors.portfolio_url?.length > 0"
              class="text-danger"
            >
              {{ errors.portfolio_url[0] }}
            </small>
          </b-col>
        </b-row>
        <b-row class="mt-4 align-items-center">
          <b-col sm="4" class="mb-2 mb-sm-0">
            <p>
              <b>{{ $t('client.account-settings.shortfilm_url.label') }}</b>
            </p>
          </b-col>
          <b-col sm="8">
            <b-input
              v-model="candidateItem.shortfilm_url"
              type="url"
              :placeholder="$t('client.account-settings.shortfilm_url.placeholder')"
            />
            <small
              v-if="errors.shortfilm_url?.length > 0"
              class="text-danger"
            >
              {{ errors.shortfilm_url[0] }}
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
      </b-card>
    </template>
  </div>
</template>

<script>
import cloneDeep from 'lodash/cloneDeep';
import hasFileInput from '@common/js/mixins/hasFileInput';
import hasJobRoleOption from '@common/js/mixins/hasJobRoleOption';
import hasLocationOptions from '@common/js/mixins/hasLocationOptions';
import hasMultipleItems from '@client/js/mixins/hasMultipleItems';

export default {
  mixins: [
    hasFileInput,
    hasJobRoleOption,
    hasLocationOptions,
    hasMultipleItems,
  ],

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
      candidateDefaultPicturePath: window[window.globalSettingsKey].candidateDefaultPicturePath,
      countryValues: Object.freeze(window[window.globalSettingsKey].countries),
      timezoneValues: Object.freeze(window[window.globalSettingsKey].timezones),
      calendarOptions: {
        minDate: new Date(),
        format: 'YYYY-MM-DD',
        dateFormatOptions: {
          day: '2-digit',
          month: '2-digit',
          year: 'numeric',
        },
        lang: {
          firstDayOfWeek: 1,
        },
      },
    };
  },

  watch: {
    async 'candidateItem.country'(value, oldValue) {
      await this.watchCountryChange(value, oldValue, 'candidateItem');
    },

    'candidateItem.region'(value, oldValue) {
      this.watchRegionChange(value, oldValue, 'candidateItem');
    },
  },

  methods: {
    cancel() {
      this.$emit('cancel');
    },

    onFileChange($event) {
      this.candidateItem.picture = $event.target.files[0];
    },

    submitForm() {
      this.$emit('submit', { candidate: this.candidateItem, blockKey: this.editableBlock });
    },
  },
};
</script>
