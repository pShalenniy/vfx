<template>
  <div id="account-settings-block">
    <h3 class="h3">
      {{ $t('client.user.account_settings.title') }}
    </h3>
    <div class="form-xs">
      <b-form>
        <hr class="mt-2" />
        <template v-if="!editableBlocks.company">
          <b-row class="align-items-center mt-2">
            <b-col>
              <div class="d-flex align-items-center flex-wrap g-3">
                <b>{{ $t('client.user.account_settings.company.label') }}</b>
                <template v-if="user.company?.logo">
                  <img
                    v-if="user.company.logo"
                    :src="user.company.logo"
                    alt=""
                    height="50"
                    width="50"
                  />
                </template>
                <template v-if="user.company?.name">
                  <b>
                    {{ user.company.name }}
                  </b>
                </template>
                <template v-if="user.company?.url">
                  <b>
                    {{ `(${user.company.url})` }}
                  </b>
                </template>
              </div>
            </b-col>
            <b-col cols="auto">
              <a
                href="#"
                class="btn btn-xs btn-rounded btn-dark float-right background-blue"
                @click.prevent="toggleEdit('company')"
              >
                {{ $t('client.user.account_settings.edit') }}
              </a>
            </b-col>
          </b-row>
        </template>

        <template v-else>
          <user-company-block
            class="font-weight-bold"
            :user="user"
            :errors="errors"
            @submit="setUserCompany"
          />

          <div class="buttons d-flex justify-content-end mt-2">
            <b-button variant="secondary" @click.prevent="toggleEdit('company')">
              {{ $t('common.cancel') }}
            </b-button>
            <b-button variant="primary" class="ml-2" @click.prevent="update('company')">
              {{ $t('common.update') }}
            </b-button>
          </div>
        </template>

        <hr class="mt-3" />
        <template v-if="!editableBlocks.job_title">
          <b-row class="align-items-center mt-2">
            <b-col>
              <p>
                <b>
                  {{ $t('client.user.account_settings.job_title.label') }}
                  {{ user.job_title }}
                </b>
              </p>
            </b-col>
            <b-col cols="auto">
              <a
                href="#"
                class="btn btn-xs btn-rounded btn-dark background-blue"
                @click.prevent="toggleEdit('job_title')"
              >
                {{ $t('client.user.account_settings.edit') }}
              </a>
            </b-col>
          </b-row>
        </template>

        <template v-else>
          <b-form-group>
            <h3>
              <b>
                {{ $t('client.user.account_settings.job_title.label') }}
              </b>
            </h3>
            <b-form-input
              v-model="user.job_title"
              type="text"
              :placeholder="$t('client.user.account_settings.job_title.placeholder')"
              required
            />
            <small
              v-if="errors.job_title?.length > 0"
              class="text-danger"
            >
              {{ errors.job_title[0] }}
            </small>
          </b-form-group>

          <div class="buttons d-flex justify-content-end mt-2">
            <b-button variant="secondary" @click.prevent="toggleEdit('job_title')">
              {{ $t('common.cancel') }}
            </b-button>
            <b-button variant="primary" class="ml-2 text-blue" @click.prevent="update('job_title')">
              {{ $t('common.update') }}
            </b-button>
          </div>
        </template>

        <hr class="mt-3" />
        <template v-if="!editableBlocks.location">
          <b-row class="align-items-center mt-2">
            <b-col>
              <p>
                <b>
                  {{ $t('client.user.account_settings.country.label') }}
                  {{ user.country?.name }}
                </b>
              </p>
            </b-col>
          </b-row>
        </template>

        <template v-else>
          <b-form-group>
            <h3>
              <b>{{ $t('client.user.account_settings.country.label') }}</b>
            </h3>
            <v-select
              v-model="user.country"
              :placeholder="$t('client.user.account_settings.country.placeholder')"
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
          </b-form-group>
        </template>

        <template v-if="!editableBlocks.location">
          <b-row class="align-items-center mt-2">
            <b-col>
              <p>
                <b>
                  {{ $t('client.user.account_settings.region.label') }}
                  {{ user.region?.name }}
                </b>
              </p>
            </b-col>
            <b-col cols="auto">
              <a
                href="#"
                class="btn btn-xs btn-rounded btn-dark background-blue"
                @click.prevent="toggleEdit('location')"
              >
                {{ $t('client.user.account_settings.edit') }}
              </a>
            </b-col>
          </b-row>
        </template>

        <template v-else>
          <b-form-group>
            <h3>
              <b>
                {{ $t('client.user.account_settings.region.label') }}
              </b>
            </h3>
            <v-select
              v-model="user.region"
              :placeholder="$t('client.user.account_settings.region.placeholder')"
              :disabled="isEmpty(user.country) || regions.length < 1"
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
          </b-form-group>
        </template>

        <template v-if="!editableBlocks.location">
          <b-row class="align-items-center mt-2">
            <b-col>
              <p>
                <b>
                  {{ $t('client.user.account_settings.city.label') }}
                  {{ user.city?.name }}
                </b>
              </p>
            </b-col>
          </b-row>
        </template>

        <template v-else>
          <b-form-group>
            <h3>
              <b>
                {{ $t('client.user.account_settings.city.label') }}
              </b>
            </h3>
            <v-select
              v-model="user.city"
              :disabled="isEmpty(user.region) || cities.length < 1"
              :placeholder="$t('client.user.account_settings.city.placeholder')"
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
          </b-form-group>

          <div class="buttons d-flex justify-content-end mt-2">
            <b-button variant="secondary" @click.prevent="toggleEdit('location')">
              {{ $t('common.cancel') }}
            </b-button>
            <b-button variant="primary" class="ml-2 text-blue" @click.prevent="update('location')">
              {{ $t('common.update') }}
            </b-button>
          </div>
        </template>

        <hr class="mt-3" />
        <template v-if="!editableBlocks.phone_number">
          <b-row class="align-items-center mt-4">
            <b-col>
              <p>
                <b>
                  {{ $t('client.user.account_settings.phone_number') }}
                  {{ user.phone_number }}
                </b>
              </p>
            </b-col>
            <b-col cols="auto">
              <a
                href="#"
                class="btn btn-xs btn-rounded btn-dark background-blue"
                @click.prevent="toggleEdit('phone_number')"
              >
                {{ $t('client.user.account_settings.edit') }}
              </a>
            </b-col>
          </b-row>
        </template>

        <template v-else>
          <b-form-group>
            <h3>
              <b>
                {{ $t('client.user.account_settings.phone_number') }}
              </b>
            </h3>
            <vue-tel-input
              :value="user.phone_number"
              @input="setPhoneNumber"
            />
            <small
              v-if="errors.phone_number?.length > 0"
              class="text-danger"
            >
              {{ errors.phone_number[0] }}
            </small>
          </b-form-group>

          <div class="buttons d-flex justify-content-end mt-2">
            <b-button variant="secondary" @click.prevent="toggleEdit('phone_number')">
              {{ $t('common.cancel') }}
            </b-button>
            <b-button
              variant="primary"
              class="ml-2 text-blue"
              @click.prevent="update('phone_number')"
            >
              {{ $t('common.update') }}
            </b-button>
          </div>
        </template>

        <hr class="mt-3" />
        <template v-if="!editableBlocks.password">
          <b-row class="align-items-center mt-2">
            <b-col>
              <p>
                <b>
                  {{ $t('client.user.account_settings.password.placeholder') }}
                </b>
              </p>
            </b-col>
            <b-col cols="auto">
              <a
                href="#"
                class="btn btn-xs btn-rounded btn-dark background-blue"
                @click.prevent="toggleEdit('password')"
              >
                {{ $t('client.user.account_settings.edit') }}
              </a>
            </b-col>
          </b-row>
        </template>

        <template v-else>
          <b-row>
            <b-col cols="6">
              <b-form-group>
                <label>
                  <b>
                    {{ $t('client.user.account_settings.password.label') }}
                  </b>
                </label>
                <b-form-input
                  v-model="user.password"
                  type="password"
                  :placeholder="$t('client.user.account_settings.password.placeholder')"
                  required
                  confirmed
                />
                <small
                  v-if="errors.password?.length > 0"
                  class="text-danger"
                >
                  {{ errors.password[0] }}
                </small>
              </b-form-group>
            </b-col>
            <b-col cols="6">
              <b-form-group>
                <label>
                  <b>
                    {{ $t('client.user.account_settings.password_confirmation.label') }}
                  </b>
                </label>
                <b-form-input
                  v-model="user.password_confirmation"
                  name="password_confirmation"
                  type="password"
                  :placeholder="$t('client.user.account_settings.password_confirmation.placeholder')"
                  required
                />
                <small
                  v-if="errors.password_confirmation?.length > 0"
                  class="text-danger"
                >
                  {{ errors.password_confirmation[0] }}
                </small>
              </b-form-group>
            </b-col>
          </b-row>

          <div class="buttons d-flex justify-content-end mt-2">
            <b-button variant="secondary" @click.prevent="toggleEdit('password')">
              {{ $t('common.cancel') }}
            </b-button>
            <b-button variant="primary" class="ml-2 text-blue" @click.prevent="update('password')">
              {{ $t('common.update') }}
            </b-button>
          </div>
        </template>

        <hr class="mt-3" />
        <b-row>
          <b-col cols="12" class="mt-2">
            <small>
              {{ $t('client.user.account_settings.disclaimer') }}
              <a :href="route('contact-us.view')">
                {{ $t('client.user.account_settings.contact_us') }}
              </a>
            </small>
          </b-col>
        </b-row>
      </b-form>
    </div>
  </div>
</template>

<script>
import cloneDeep from 'lodash/cloneDeep';
import jsonToFormData from 'json-form-data';
import { useUserStore } from '@common/js/store/user';
import hasLocationOptions from '@common/js/mixins/hasLocationOptions';
import hasPhoneNumber from '@client/js/mixins/hasPhoneNumber';
import hasDataSilentlySetter from '@common/js/mixins/hasDataSilentlySetter';
import hasUserCompanyOption from '@common/js/mixins/hasUserCompanyOption';
import UserCompanyBlock from '@common/js/components/user-company/UserCompanyBlock';

export default {
  components: {
    UserCompanyBlock,
  },

  mixins: [
    hasDataSilentlySetter,
    hasLocationOptions,
    hasPhoneNumber,
    hasUserCompanyOption,
  ],

  data() {
    return {
      user: this.getPrefilledUser(window[window.globalSettingsKey].currentUser),
      errors: {},
      editableBlocks: {
        company: false,
        job_title: false,
        location: false,
        phone_number: false,
        password: false,
      },
    };
  },

  watch: {
    async 'user.country'(value, oldValue) {
      await this.watchCountryChange(value, oldValue, 'user');
    },

    'user.region'(value, oldValue) {
      this.watchRegionChange(value, oldValue, 'user');
    },
  },

  async created() {
    this.$overlay.show();

    try {
      await this.getLocationOptions('user');
    } catch (e) {
      console.error(e);
    } finally {
      this.$overlay.hide();
    }
  },

  methods: {
    getPrefilledUser(data = {}) {
      const clonedData = cloneDeep(data);
      const user = {
        company: {},
        job_title: null,
        city: {},
        region: {},
        country: {},
        phone_number: null,
        password: null,
        password_confirmation: null,
      };

      Object.keys(user).forEach((key) => {
        user[key] = clonedData[key] || user[key];
      });

      return user;
    },

    setPrefilledUser(data = {}) {
      const user = this.getPrefilledUser(data);

      Object.keys(user).forEach((key) => {
        this.setDataSilently(`user.${key}`, () => {
          this.user[key] = user[key];
        });
      });
    },

    async update(blockKey) {
      this.$overlay.show();

      this.errors = {};

      const user = jsonToFormData(
        Object.assign(
          { blockKey },
          this.prepareUserLocationData(cloneDeep(this.user)),
          { _method: 'PATCH' },
        ),
      );

      try {
        const userStore = useUserStore();

        const { data } = await axios.post(route('user.update'), user);

        this.setPrefilledUser(data.data);

        await userStore.addUser({ user: data.data });

        this.$notify.success(this.$t('client.user.account_settings.notification.success'));

        Object.keys(this.editableBlocks).forEach((key) => {
          this.editableBlocks[key] = false;
        });
      } catch (error) {
        console.error(error);
        this.$notify.errors(error);
        this.errors = error.response?.data?.errors || {};
      } finally {
        this.$overlay.hide();
      }
    },

    toggleEdit(field) {
      this.editableBlocks[field] = !this.editableBlocks[field];
    },

    setUserCompany(userCompany) {
      this.user.company = userCompany;
    },
  },
};
</script>
