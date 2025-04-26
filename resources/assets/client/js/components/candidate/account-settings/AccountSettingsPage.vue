<template>
  <div
    id="candidate-account-settings-page"
    class="section bg-primary text-white"
  >
    <div class="container">
      <div class="mb-4">
        <b-tabs pills align="center" nav-class="mb-4">
          <b-tab
            :title="$t('client.account-settings.title')"
            active
          >
            <div class="mb-4 candidate-profile__card w-100 card p-4 p-lg-5 text-body">
              <b-row v-show="editableBlock !== 'main_information'" class="mb-3">
                <b-col cols="auto" class="ml-auto">
                  <a
                    href="#"
                    class="btn btn-xs btn-rounded btn-dark mb-2 background-blue"
                    @click.prevent="toggleEdit('main_information')"
                  >
                    {{ $t('client.account-settings.buttons.edit') }}
                  </a>
                </b-col>
              </b-row>

              <main-information-block
                :candidate="candidate"
                :editable-block="editableBlock"
                :errors="errors"
                @cancel="toggleEdit"
                @submit="update"
              />
            </div>
            <div class="candidate-profile__card w-100 card p-4 p-lg-5 text-body mb-4">
              <div class="mt-4">
                <b-row v-show="editableBlock !== 'general'" class="align-items-center">
                  <b-col>
                    <h4 class="h4 mb-0 text-light-blue">
                      {{ $t('client.account-settings.blocks.general') }}
                    </h4>
                  </b-col>
                  <b-col cols="auto" class="ml-auto">
                    <a
                      href="#"
                      class="btn btn-xs btn-rounded btn-dark background-blue"
                      @click.prevent="toggleEdit('general')"
                    >
                      {{ $t('client.account-settings.buttons.edit') }}
                    </a>
                  </b-col>
                </b-row>

                <general-block
                  :candidate="candidate"
                  :editable-block="editableBlock"
                  :errors="errors"
                  @cancel="toggleEdit"
                  @submit="update"
                />
              </div>
              <div class="mt-4">
                <b-row v-show="editableBlock !== 'interests'" class="align-items-center">
                  <b-col>
                    <h4 class="h4 mb-0 text-light-blue">
                      {{ $t('client.account-settings.blocks.interests') }}
                    </h4>
                  </b-col>
                  <b-col cols="auto" class="ml-auto">
                    <a
                      href="#"
                      class="btn btn-xs btn-rounded btn-dark background-blue"
                      @click.prevent="toggleEdit('interests')"
                    >
                      {{ $t('client.account-settings.buttons.edit') }}
                    </a>
                  </b-col>
                </b-row>

                <interests-block
                  :candidate="candidate"
                  :editable-block="editableBlock"
                  :errors="errors"
                  @cancel="toggleEdit"
                  @submit="update"
                />
              </div>
              <div class="mt-4">
                <b-row v-show="editableBlock !== 'personal'" class="align-items-center">
                  <b-col>
                    <h4 class="h4 mb-0 text-light-blue">
                      {{ $t('client.account-settings.blocks.personal') }}
                    </h4>
                  </b-col>
                  <b-col cols="auto" class="ml-auto">
                    <a
                      href="#"
                      class="btn btn-xs btn-rounded btn-dark background-blue"
                      @click.prevent="toggleEdit('personal')"
                    >
                      {{ $t('client.account-settings.buttons.edit') }}
                    </a>
                  </b-col>
                </b-row>

                <personal-block
                  :candidate="candidate"
                  :editable-block="editableBlock"
                  :errors="errors"
                  @cancel="toggleEdit"
                  @submit="update"
                />
              </div>
              <div class="mt-4">
                <b-row v-show="editableBlock !== 'contact'" class="align-items-center">
                  <b-col>
                    <h4 class="h4 mb-0 text-light-blue">
                      {{ $t('client.account-settings.blocks.contact') }}
                    </h4>
                  </b-col>
                  <b-col cols="auto" class="ml-auto">
                    <a
                      href="#"
                      class="btn btn-xs btn-rounded btn-dark background-blue"
                      @click.prevent="toggleEdit('contact')"
                    >
                      {{ $t('client.account-settings.buttons.edit') }}
                    </a>
                  </b-col>
                </b-row>

                <contact-block
                  :candidate="candidate"
                  :editable-block="editableBlock"
                  :errors="errors"
                  @cancel="toggleEdit"
                  @submit="update"
                />
              </div>
            </div>
          </b-tab>
          <b-tab :title="$t('client.account-settings.blocks.password')">
            <div class="mb-4 candidate-profile__card w-100 card p-4 p-lg-5 text-body">
              <password-block
                :candidate="candidate"
                :errors="errors"
                @submit="update"
              />
            </div>
          </b-tab>
        </b-tabs>

        <viewed-companies-block :candidate="candidate" />
      </div>
    </div>
  </div>
</template>

<script>
import cloneDeep from 'lodash/cloneDeep';
import jsonToFormData from 'json-form-data';
import budgetOfBiggestShowValues from '@common/js/constants/candidateBudgetOfBiggestShowConstants';
import salaryRateCurrencyValues from '@common/js/constants/candidateSalaryRateCurrencyConstants';
import hasPreparedCandidateData from '@common/js/mixins/hasPreparedCandidateData';
import ContactBlock from '@client/js/components/candidate/account-settings/ContactBlock';
import GeneralBlock from '@client/js/components/candidate/account-settings/GeneralBlock';
import InterestsBlock from '@client/js/components/candidate/account-settings/InterestsBlock';
import MainInformationBlock from '@client/js/components/candidate/account-settings/MainInformationBlock';
import PasswordBlock from '@client/js/components/candidate/account-settings/PasswordBlock';
import PersonalBlock from '@client/js/components/candidate/account-settings/PersonalBlock';
import ViewedCompaniesBlock from '@client/js/components/candidate/account-settings/ViewedCompaniesBlock';

export default {
  components: {
    ContactBlock,
    GeneralBlock,
    InterestsBlock,
    MainInformationBlock,
    PasswordBlock,
    PersonalBlock,
    ViewedCompaniesBlock,
  },

  mixins: [
    hasPreparedCandidateData,
  ],

  data() {
    return {
      candidate: this.getPrefilledCandidate(),
      editableBlock: null,
      salaryRateCurrencyValues: Object.values(salaryRateCurrencyValues),
      budgetOfBiggestShowValues: Object.values(budgetOfBiggestShowValues),
      errors: [],
    };
  },

  methods: {
    async update(item) {
      this.$overlay.show();

      this.errors = [];

      const candidateData = jsonToFormData(
        Object.assign(
          {},
          this.getPreparedCandidate(item.candidate),
          { blockKey: item.blockKey },
          { _method: 'PATCH' },
        ),
      );

      try {
        const { data } = await axios.post(route('candidate.account-settings.update'),
          candidateData,
          {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          },
        );

        if (data.data) {
          this.candidate = this.getPrefilledCandidate(data.data);

          this.toggleEdit();
        }

        this.$notify.success(this.$t('client.account-settings.actions.edit.success'));
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

    getPrefilledCandidate() {
      const fields = [
        'first_name',
        'last_name',
        'city',
        'region',
        'country',
        'timezone',
        'company',
        'television_shows',
        'alternative_citizenship_residencies',
        'budget_of_biggest_show',
        'phone_number',
        'gross_annual_salary',
        'week_rate',
        'day_rate',
        'commercial_experience',
        'commercial_experience_years',
        'preferred_sectors',
        'preferred_locations',
        'travel_availability',
        'salary_rate_currency',
        'vfx_notes',
        'picture',
        'skills',
        'want_learn_skills',
        'want_work_with_tools',
        'desired_job_roles',
        'current_job_roles',
        'next_promotion_job_roles',
        'preferred_work_environments',
        'nationalities',
        'would_like_work_on',
        'email',
        'imdb_link',
        'linkedin_link',
        'instagram_link',
        'twitter_link',
        'next_availability',
        'professional_interest',
        'portfolio_url',
        'shortfilm_url',
        'password',
        'password_confirmation',
      ];

      const cloneCandidate = cloneDeep(window[window.globalSettingsKey].candidate);
      const candidate = {};

      fields.forEach((key) => {
        if (key === 'salary_rate_currency') {
          const salaryRateCurrencyData = Object.keys(salaryRateCurrencyValues).find((item) => {
            return cloneCandidate[key] === salaryRateCurrencyValues[item].label;
          });

          candidate[key] = {
            value: salaryRateCurrencyValues[salaryRateCurrencyData]?.value,
            label: salaryRateCurrencyValues[salaryRateCurrencyData]?.label,
          };
        } else if (key === 'budget_of_biggest_show') {
          const budgetOfBiggestShowData = Object.keys(budgetOfBiggestShowValues).find((item) => {
            return cloneCandidate[key] === budgetOfBiggestShowValues[item].value;
          });

          candidate[key] = {
            value: cloneCandidate[key],
            label: budgetOfBiggestShowValues[budgetOfBiggestShowData]?.label,
          };
        } else {
          candidate[key] = cloneCandidate[key];
        }
      });

      return candidate;
    },

    toggleEdit(field = null) {
      this.editableBlock = field;
    },
  },
};
</script>
