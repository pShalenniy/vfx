<template>
  <b-form @submit="submitForm">
    <b-card border-variant="primary">
      <b-row>
        <b-col cols="6" md="6">
          <b-form-group>
            <label>{{ $t('admin.user.form.first_name') }}</label>
            <b-input
              v-model="userData.first_name"
              type="text"
              :placeholder="$t('admin.user.form.first_name')"
              required
            />
            <small
              v-if="errors.first_name?.length > 0"
              class="text-danger"
            >
              {{ errors.first_name[0] }}
            </small>
          </b-form-group>
        </b-col>
        <b-col cols="6" md="6">
          <b-form-group>
            <label>{{ $t('admin.user.form.last_name') }}</label>
            <b-input
              v-model="userData.last_name"
              type="text"
              :placeholder="$t('admin.user.form.last_name')"
              required
            />
            <small
              v-if="errors.last_name?.length > 0"
              class="text-danger"
            >
              {{ errors.last_name[0] }}
            </small>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="6" md="6">
          <b-form-group>
            <label>{{ $t('admin.user.form.email') }}</label>
            <b-input
              v-model="userData.email"
              type="email"
              :placeholder="$t('admin.user.form.email')"
              required
            />
            <small
              v-if="errors.email?.length > 0"
              class="text-danger"
            >
              {{ errors.email[0] }}
            </small>
          </b-form-group>
        </b-col>
        <b-col cols="6" md="6">
          <b-form-group>
            <label>{{ $t('admin.user.form.preferred_job_roles') }}</label>
            <v-select
              v-model="userData.preferred_job_roles"
              :placeholder="$t('admin.user.form.preferred_job_roles')"
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
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="6" md="6">
          <b-form-group>
            <label>{{ $t('admin.candidate.form.country') }}</label>
            <v-select
              v-model="userData.country"
              :placeholder="$t('admin.candidate.form.country')"
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
        </b-col>
        <b-col cols="6" md="6">
          <b-form-group>
            <label>{{ $t('admin.candidate.form.region') }}</label>
            <v-select
              v-model="userData.region"
              :placeholder="$t('admin.candidate.form.region')"
              :disabled="isEmpty(userData.country) || regions.length < 1"
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
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="6" md="6">
          <b-form-group>
            <label>{{ $t('admin.candidate.form.city') }}</label>
            <v-select
              v-model="userData.city"
              :disabled="isEmpty(userData.region) || cities.length < 1"
              :placeholder="$t('admin.candidate.form.city')"
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
        </b-col>
        <b-col cols="6" md="6">
          <b-form-group>
            <label>{{ $t('admin.user.form.job_title') }}</label>
            <b-input
              v-model="userData.job_title"
              type="text"
              :placeholder="$t('admin.user.form.job_title')"
              required
            />
            <small
              v-if="errors.job_title?.length > 0"
              class="text-danger"
            >
              {{ errors.job_title[0] }}
            </small>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="6" md="6">
          <b-form-group>
            <label>{{ $t('admin.user.form.phone_number') }}</label>
            <vue-tel-input
              :value="userData.phone_number"
              @input="setPhoneNumber"
            />
            <small
              v-if="errors.phone_number?.length > 0"
              class="text-danger"
            >
              {{ errors.phone_number[0] }}
            </small>
          </b-form-group>
        </b-col>
        <b-col cols="6" md="6">
          <b-form-group>
            <label>{{ $t('admin.user.form.role') }}</label>
            <v-select
              v-model="userData.role_id"
              :placeholder="$t('admin.user.form.role')"
              value="id"
              label="name"
              :options="roles"
            />
            <small
              v-if="errors.role_id?.length > 0"
              class="text-danger"
            >
              {{ errors.role_id[0] }}
            </small>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="6" md="6">
          <b-form-group>
            <label>{{ $t('admin.user.form.password') }}</label>
            <b-form-input
              v-model="userData.password"
              type="password"
              :placeholder="$t('admin.user.form.password')"
              required
            />
            <small
              v-if="errors.password?.length > 0"
              class="text-danger"
            >
              {{ errors.password[0] }}
            </small>
          </b-form-group>
        </b-col>
        <b-col cols="6" md="6">
          <b-form-group>
            <label>{{ $t('admin.user.form.password_confirmation') }}</label>
            <b-form-input
              v-model="userData.password_confirmation"
              type="password"
              :placeholder="$t('admin.user.form.password_confirmation')"
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
      <b-row v-if="!user.id">
        <b-col cols="6" md="6">
          <b-form-checkbox
            v-model="userData.notify_user"
            class="mb-1"
            :value="true"
            :unchecked-value="false"
          >
            {{ $t('admin.user.form.notify_user') }}
          </b-form-checkbox>
        </b-col>
        <b-col cols="6" md="6">
          <b-form-checkbox
            v-model="userData.is_verified"
            class="mb-1"
            :value="true"
            :unchecked-value="false"
          >
            {{ $t('admin.user.form.is_verified') }}
          </b-form-checkbox>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="6" md="6">
          <b-form-group>
            <label>{{ $t('admin.user.form.has_signatory.title') }}</label>
            <b-form-checkbox
              v-model="userData.has_signatory"
              :value="true"
              :unchecked-value="false"
            >
              {{ $t('admin.user.form.has_signatory.text') }}
            </b-form-checkbox>
            <small
              v-if="errors.has_signatory?.length > 0"
              class="text-danger"
            >
              {{ errors.has_signatory[0] }}
            </small>
          </b-form-group>
        </b-col>
        <b-col cols="6" md="6">
          <user-company-block
            :user="userData"
            :errors="errors"
            @submit="setUserCompany"
          />
        </b-col>
      </b-row>
    </b-card>
    <b-card :title="$t('admin.user.form.subscription.title')" border-variant="primary">
      <b-row>
        <b-col cols="12" md="12">
          <b-form-group>
            <label>{{ $t('admin.user.form.subscription.departments') }}</label>
            <v-select
              v-model="userData.subscription.departments"
              :placeholder="$t('admin.user.form.subscription.departments')"
              multiple
              label="name"
              value="id"
              :options="departments"
              :close-on-select="false"
            />
            <small
              v-if="errors['subscription.departments']?.length > 0"
              class="text-danger"
            >
              {{ errors['subscription.departments'][0] }}
            </small>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="6" md="6">
          <b-form-group>
            <label>{{ $t('admin.user.form.subscription.status') }}</label>
            <v-select
              :value="userData.subscription.status"
              :options="subscriptionStatusValues"
              :placeholder="$t('admin.user.form.subscription.status')"
              @input="setSubscriptionStatus"
            />
            <small
              v-if="errors['subscription.status']?.length > 0"
              class="text-danger"
            >
              {{ errors['subscription.status'][0] }}
            </small>
          </b-form-group>
        </b-col>
        <b-col cols="6" md="6">
          <b-form-group>
            <label>{{ $t('admin.user.form.subscription.seats') }}</label>
            <b-input
              v-model="userData.subscription.seats"
              type="number"
              min="1"
              :placeholder="$t('admin.user.form.subscription.seats')"
              required
            />
            <small
              v-if="errors['subscription.seats']?.length > 0"
              class="text-danger"
            >
              {{ errors['subscription.seats'][0] }}
            </small>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="6" md="6">
          <b-form-group>
            <label>{{ $t('admin.user.form.subscription.period') }}</label>
            <v-select
              v-model="userData.subscription.period"
              :options="subscriptionPeriodValues"
              :placeholder="$t('admin.user.form.subscription.period')"
            />
            <small
              v-if="errors['subscription.period']?.length > 0"
              class="text-danger"
            >
              {{ errors['subscription.period'][0] }}
            </small>
          </b-form-group>
        </b-col>
        <b-col cols="6" md="6">
          <b-form-checkbox
            v-model="userData.subscription.contract_signed"
            class="mb-1 mt-2"
            :value="true"
            :unchecked-value="false"
          >
            {{ $t('admin.user.form.subscription.contract_signed') }}
          </b-form-checkbox>
        </b-col>
      </b-row>
    </b-card>
  </b-form>
</template>

<script>
import cloneDeep from 'lodash/cloneDeep';
import subscriptionPeriodValues from '@admin/js/constants/subscriptionPeriodConstants';
import subscriptionStatusValues from '@admin/js/constants/subscriptionStatusConstants';
import hasJobRoleOption from '@common/js/mixins/hasJobRoleOption';
import hasLocationOptions from '@common/js/mixins/hasLocationOptions';
import UserCompanyBlock from '@common/js/components/user-company/UserCompanyBlock';

export default {
  components: {
    UserCompanyBlock,
  },

  mixins: [
    hasJobRoleOption,
    hasLocationOptions,
  ],

  props: {
    user: {
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
      subscriptionPeriodValues: Object.values(subscriptionPeriodValues),
      subscriptionStatusValues: Object.values(subscriptionStatusValues),
      userData: cloneDeep(this.user),
      roles: window[window.globalSettingsKey].roles,
      departments: Object.freeze(window[window.globalSettingsKey].departments),
      subscriptionStatus: {},
    };
  },

  watch: {
    async 'userData.country'(value, oldValue) {
      await this.watchCountryChange(value, oldValue, 'userData');
    },

    'userData.region'(value, oldValue) {
      this.watchRegionChange(value, oldValue, 'userData');
    },
  },

  async created() {
    this.$overlay.show();

    try {
      await this.getLocationOptions('userData');
    } catch (e) {
      console.error(e);
    } finally {
      this.$overlay.hide();
    }
  },

  methods: {
    async setSubscriptionStatus(value) {
      let status = !this.isEmpty(value) ? value : {};

      switch (value?.value) {
        case subscriptionStatusValues.STATUS_ACTIVE.value:
          status = await this.getActiveSubscriptionStatus();
          break;

        case subscriptionStatusValues.STATUS_CANCELLED.value:
          status = await this.getCancelledSubscriptionStatus();
          break;

        case subscriptionStatusValues.STATUS_PAUSED.value:
          status = await this.getPausedSubscriptionStatus();
          break;
      }

      this.userData.subscription.status = status;
    },

    submitForm() {
      let user = {};

      user = this.prepareUserLocationData(cloneDeep(this.userData));

      user = this.preparePreferredJobRole(user);

      user = this.prepareUserSubscriptionData(user);

      if (!user.phone_number) {
        user.phone_number = null;
      }

      this.$emit('submit', { ...user, role_id: user.role_id?.id });
    },

    prepareUserSubscriptionData(user) {
      const numberFields = [
        'period',
        'status',
      ];

      const departments = [];

      if (!this.isEmpty(user.subscription.departments)) {
        delete user.subscription.departments;

        this.userData.subscription.departments.forEach((department) => {
          departments.push(department.id);
        });

        user.subscription.departments = departments;

        for (const numberField of numberFields) {
          if (user.subscription[numberField] && user.subscription[numberField]?.value) {
            delete user.subscription[numberField];

            user.subscription[numberField] = Number(this.userData.subscription[numberField].value);
          }
        }
      } else {
        delete user.subscription;
      }

      return user;
    },

    async getActiveSubscriptionStatus() {
      try {
        let result = await this.$confirm.confirm({
          title: this.$t('admin.user.form.subscription.status_changes_confirmation.title'),
          text: this.$t('admin.user.form.subscription.status_changes_confirmation.questions.active.question_1'),
          icon: 'info',
        });

        if (!result.isConfirmed) {
          return subscriptionStatusValues.STATUS_PENDING_DEMO;
        }

        result = await this.$confirm.confirm({
          title: this.$t('admin.user.form.subscription.status_changes_confirmation.title'),
          text: this.$t('admin.user.form.subscription.status_changes_confirmation.questions.active.question_2'),
          icon: 'info',
        });

        return result.isConfirmed
          ? subscriptionStatusValues.STATUS_ACTIVE
          : subscriptionStatusValues.STATUS_PENDING_DEMO;
      } catch (e) {
        console.error(e);
      }
    },

    async getCancelledSubscriptionStatus() {
      try {
        const result = await this.$confirm.confirm({
          title: this.$t('admin.user.form.subscription.status_changes_confirmation.title'),
          text: this.$t('admin.user.form.subscription.status_changes_confirmation.questions.cancelled.question'),
          icon: 'info',
        });

        return result.isConfirmed
          ? subscriptionStatusValues.STATUS_CANCELLED
          : subscriptionStatusValues.STATUS_ACTIVE;
      } catch (e) {
        console.error(e);
      }
    },

    async getPausedSubscriptionStatus() {
      try {
        const result = await this.$confirm.confirm({
          title: this.$t('admin.user.form.subscription.status_changes_confirmation.title'),
          text: this.$t('admin.user.form.subscription.status_changes_confirmation.questions.paused.question'),
          icon: 'info',
        });

        return result.isConfirmed
          ? subscriptionStatusValues.STATUS_PAUSED
          : subscriptionStatusValues.STATUS_ACTIVE;
      } catch (e) {
        console.error(e);
      }
    },

    setPhoneNumber(phoneNumber, phoneNumberData) {
      // todo: make it better
      this.userData.phone_number = phoneNumberData.number;
    },

    setUserCompany(userCompany) {
      this.userData.company = userCompany;
    },
  },
};
</script>
