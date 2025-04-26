<template>
  <div id="register-page" class="section-auth-block">
    <h1 class="h1 section-title text-center text-secondary">
      {{ $t('client.register.title') }}
    </h1>
    <h2 class="h3 text-center text-secondary">
      {{ $t('client.register.subtitle') }}
    </h2>
    <b-form>
      <b-form-group :label="$t('client.register.form.first_name')">
        <b-form-input
          v-model="user.first_name"
          type="text"
          :placeholder="$t('client.register.form.first_name')"
          required
        />
        <small
          v-if="errors.first_name?.length > 0"
          class="text-danger"
        >
          {{ errors.first_name[0] }}
        </small>
      </b-form-group>
      <b-form-group :label="$t('client.register.form.last_name')">
        <b-form-input
          v-model="user.last_name"
          type="text"
          :placeholder="$t('client.register.form.last_name')"
          required
        />
        <small
          v-if="errors.last_name?.length > 0"
          class="text-danger"
        >
          {{ errors.last_name[0] }}
        </small>
      </b-form-group>
      <b-form-group>
        <user-company-block
          :user="user"
          :is-edit="false"
          :errors="errors"
          @submit="setUserCompany"
        />
      </b-form-group>
      <b-form-group :label="$t('client.register.form.job_title')">
        <b-form-input
          v-model="user.job_title"
          type="text"
          :placeholder="$t('client.register.form.job_title')"
          required
        />
        <small
          v-if="errors.job_title?.length > 0"
          class="text-danger"
        >
          {{ errors.job_title[0] }}
        </small>
      </b-form-group>
      <b-form-group>
        <label>{{ $t('client.register.form.country') }}</label>
        <v-select
          v-model="user.country"
          :placeholder="$t('client.register.form.country')"
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
      <b-form-group>
        <label>{{ $t('client.register.form.region') }}</label>
        <v-select
          v-model="user.region"
          :placeholder="$t('client.register.form.region')"
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
      <b-form-group>
        <label>{{ $t('client.register.form.city') }}</label>
        <v-select
          v-model="user.city"
          :disabled="isEmpty(user.region) || cities.length < 1"
          :placeholder="$t('client.register.form.city')"
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
      <b-form-group :label="$t('client.register.form.email')">
        <b-form-input
          v-model="user.email"
          type="email"
          :placeholder="$t('client.register.form.email')"
          required
        />
        <small
          v-if="errors.email?.length > 0"
          class="text-danger"
        >
          {{ errors.email[0] }}
        </small>
      </b-form-group>

      <b-form-group>
        <label>{{ $t('client.register.form.phone_number') }}</label>
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
      <b-form-group>
        <label>{{ $t('client.register.form.preferred_job_roles') }}</label>
        <v-select
          v-model="user.preferred_job_roles"
          :placeholder="$t('client.register.form.preferred_job_roles')"
          :options="preferredJobRoles"
          label="name"
          value="id"
          multiple
          taggable
          :close-on-select="false"
          @search="(keyword, loading) => onSearchJobRoles(keyword, loading, 'preferredJobRoles')"
        />
        <small
          v-if="errors.preferred_job_roles?.length > 0"
          class="text-danger"
        >
          {{ errors.preferred_job_roles[0] }}
        </small>
      </b-form-group>
      <b-form-group>
        <label>{{ $t('client.register.form.has_signatory.title') }}</label>
        <b-form-checkbox
          v-model="user.has_signatory"
          :value="true"
          :unchecked-value="false"
        >
          {{ $t('client.register.form.has_signatory.text') }}
        </b-form-checkbox>
        <small
          v-if="errors.has_signatory?.length > 0"
          class="text-danger"
        >
          {{ errors.has_signatory[0] }}
        </small>
      </b-form-group>
      <b-form-group :label="$t('client.register.form.password')">
        <b-form-input
          v-model="user.password"
          type="password"
          :placeholder="$t('client.register.form.password')"
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
      <b-form-group
        class="mb-2"
        :label="$t('client.register.form.password_confirmation')"
      >
        <b-form-input
          v-model="user.password_confirmation"
          name="password_confirmation"
          type="password"
          :placeholder="$t('client.register.form.password_confirmation')"
          required
        />
        <small
          v-if="errors.password_confirmation?.length > 0"
          class="text-danger"
        >
          {{ errors.password_confirmation[0] }}
        </small>
      </b-form-group>
      <input
        type="submit"
        :value="$t('client.register.form.submit_button')"
        class="btn btn-primary text-blue"
        @click.prevent="register"
      />
    </b-form>
    <p>
      {{ $t('client.register.form.link.label') }}
      <a :href="route('login.view')" class="text-primary">
        {{ $t('client.register.form.link.login') }}
      </a>
    </p>
  </div>
</template>

<script>
import cloneDeep from 'lodash/cloneDeep';
import jsonToFormData from 'json-form-data';
import hasJobRoleOption from '@common/js/mixins/hasJobRoleOption';
import hasLocationOptions from '@common/js/mixins/hasLocationOptions';
import hasPhoneNumber from '@client/js/mixins/hasPhoneNumber';
import hasUserCompanyOption from '@common/js/mixins/hasUserCompanyOption';
import UserCompanyBlock from '@common/js/components/user-company/UserCompanyBlock';

export default {
  components: {
    UserCompanyBlock,
  },

  mixins: [
    hasJobRoleOption,
    hasLocationOptions,
    hasPhoneNumber,
    hasUserCompanyOption,
  ],

  data() {
    return {
      user: {
        first_name: null,
        last_name: null,
        email: null,
        company: {
          id: null,
          name: null,
          logo: null,
          url: null,
        },
        country: {},
        region: {},
        city: {},
        job_title: null,
        preferred_job_roles: [],
        phone_number: null,
        has_signatory: false,
        password: null,
        password_confirmation: null,
      },
      errors: [],
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

  methods: {
    async register() {
      this.$overlay.show();

      this.errors = [];

      const user = this.prepareUserLocationData(cloneDeep(this.user));

      const userData = jsonToFormData(
        Object.assign(
          {},
          this.preparePreferredJobRole(user),
        ),
      );

      try {
        const { data } = await axios.post(route('sign-up.post'),
          userData,
          {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          },
        );

        if (data?.data) {
          this.$notify.success(data.data);
        } else {
          this.$notify.success(this.$t('client.register.notification.sign_up.success'));
        }

        window.location.href = route('login.view');
      } catch (error) {
        if (error.response.data.errors) {
          this.errors = error.response.data.errors;
          console.error(error.response.data.errors);
        } else if (error.response.data.message) {
          this.$notify.errors(error.response.data.message);
          console.error(error.response.data.message);
        }
      } finally {
        this.$overlay.hide();
      }
    },

    setUserCompany(userCompany) {
      this.user.company = userCompany;
    },
  },
};
</script>
