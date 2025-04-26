<template>
  <b-form @submit="submitForm">
    <key-skill-level-modal @selected="setSkillLevel" />

    <b-row>
      <b-col cols="6">
        <b-card :title="$t('admin.candidate.form.groups.personal')" border-variant="primary">
          <b-row>
            <b-col cols="6" md="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.first_name') }}</label>
                <b-input
                  v-model="candidateData.first_name"
                  type="text"
                  :placeholder="$t('admin.candidate.form.first_name')"
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
                <label>{{ $t('admin.candidate.form.last_name') }}</label>
                <b-input
                  v-model="candidateData.last_name"
                  type="text"
                  :placeholder="$t('admin.candidate.form.last_name')"
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
            <b-col cols="12" md="12">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.picture.label') }}</label>
                <b-form-file
                  v-model="candidateData.picture"
                  :placeholder="$t('admin.candidate.form.picture.placeholder')"
                  :drop-placeholder="$t('admin.candidate.form.picture.drop_placeholder')"
                  :accept="getAcceptableExtensions('image')"
                  @change="onFileChange($event)"
                />
                <small
                  v-if="errors.picture?.length > 0"
                  class="text-danger"
                >
                  {{ errors.picture[0] }}
                </small>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col cols="6" md="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.nationalities') }}</label>
                <v-select
                  v-model="candidateData.nationalities"
                  :placeholder="$t('admin.candidate.form.nationalities')"
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
              </b-form-group>
            </b-col>
            <b-col cols="6" md="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.alternative_citizenship_residencies') }}</label>
                <v-select
                  v-model="candidateData.alternative_citizenship_residencies"
                  :placeholder="$t('admin.candidate.form.alternative_citizenship_residencies')"
                  :options="countryValues"
                  label="name"
                  value="id"
                  multiple
                  :close-on-select="false"
                />
                <small
                  v-if=" errors.alternative_citizenship_residencies?.length > 0"
                  class="text-danger"
                >
                  {{ errors.alternative_citizenship_residencies[0] }}
                </small>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col cols="6" md="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.country') }}</label>
                <v-select
                  v-model="candidateData.country"
                  :placeholder="$t('admin.candidate.form.country')"
                  label="name"
                  value="id"
                  :options="countryValues"
                />
                <small
                  v-if=" errors.country?.length > 0"
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
                  v-model="candidateData.region"
                  :placeholder="$t('admin.candidate.form.region')"
                  :disabled="isEmpty(candidateData.country) || regions.length < 1"
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
                  v-model="candidateData.city"
                  :disabled="isEmpty(candidateData.region) || cities.length < 1"
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
                <label>{{ $t('admin.candidate.form.timezone') }}</label>
                <v-select
                  v-model="candidateData.timezone"
                  :placeholder="$t('admin.candidate.form.timezone')"
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
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col cols="6" md="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.preferred_location') }}</label>
                <v-select
                  v-model="candidateData.preferred_locations"
                  :placeholder="$t('admin.candidate.form.preferred_location')"
                  :options="preferredLocations"
                  label="name"
                  value="id"
                  multiple
                  taggable
                  :close-on-select="false"
                  :create-option="vueSelectCreateOption"
                  @search="onSearchPreferredLocations"
                />
                <small
                  v-if="errors.preferred_locations?.length > 0"
                  class="text-danger"
                >
                  {{ errors.preferred_locations[0] }}
                </small>
              </b-form-group>
            </b-col>
            <b-col cols="6" md="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.next_availability') }}</label>
                <b-form-datepicker
                  v-model="candidateData.next_availability"
                  :date-format-options="calendarOptions.dateFormatOptions"
                  :start-weekday="calendarOptions.startWeekday"
                  :locale="calendarOptions.locale"
                  :placeholder="$t('admin.candidate.form.next_availability')"
                />
                <small
                  v-if="errors.next_availability?.length > 0"
                  class="text-danger"
                >
                  {{ errors.next_availability[0] }}
                </small>
              </b-form-group>
            </b-col>
          </b-row>
        </b-card>

        <b-card :title="$t('admin.candidate.form.groups.contact_social')" border-variant="primary">
          <b-row>
            <b-col cols="6" md="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.email') }}</label>
                <b-input
                  v-model="candidateData.email"
                  type="email"
                  :placeholder="$t('admin.candidate.form.email')"
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
                <label>{{ $t('admin.candidate.form.cell_phone_number') }}</label>
                <b-input-group prepend="+">
                  <b-input
                    v-model="candidateData.phone_number"
                    type="number"
                    :placeholder="$t('admin.candidate.form.cell_phone_number')"
                    required
                  />
                </b-input-group>
                <small
                  v-if="errors.phone_number?.length > 0"
                  class="text-danger"
                >
                  {{ errors.phone_number[0] }}
                </small>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col cols="6" md="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.portfolio_url') }}</label>
                <b-input
                  v-model="candidateData.portfolio_url"
                  type="url"
                  :placeholder="$t('admin.candidate.form.portfolio_url')"
                />
                <small
                  v-if="errors.portfolio_url?.length > 0"
                  class="text-danger"
                >
                  {{ errors.portfolio_url[0] }}
                </small>
              </b-form-group>
            </b-col>
            <b-col cols="6" md="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.shortfilm_url') }}</label>
                <b-input
                  v-model="candidateData.shortfilm_url"
                  type="url"
                  :placeholder="$t('admin.candidate.form.shortfilm_url')"
                />
                <small
                  v-if="errors.shortfilm_url?.length > 0"
                  class="text-danger"
                >
                  {{ errors.shortfilm_url[0] }}
                </small>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col cols="6" md="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.imdb_link') }}</label>
                <b-input
                  v-model="candidateData.imdb_link"
                  type="url"
                  :placeholder="$t('admin.candidate.form.imdb_link')"
                />
                <small
                  v-if="errors.imdb_link?.length > 0"
                  class="text-danger"
                >
                  {{ errors.imdb_link[0] }}
                </small>
              </b-form-group>
            </b-col>
            <b-col cols="6" md="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.linkedin_link') }}</label>
                <b-input
                  v-model="candidateData.linkedin_link"
                  type="url"
                  :placeholder="$t('admin.candidate.form.linkedin_link')"
                />
                <small
                  v-if="errors.linkedin_link?.length > 0"
                  class="text-danger"
                >
                  {{ errors.linkedin_link[0] }}
                </small>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col cols="6" md="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.instagram_link') }}</label>
                <b-input
                  v-model="candidateData.instagram_link"
                  type="url"
                  :placeholder="$t('admin.candidate.form.instagram_link')"
                />
                <small
                  v-if="errors.instagram_link?.length > 0"
                  class="text-danger"
                >
                  {{ errors.instagram_link[0] }}
                </small>
              </b-form-group>
            </b-col>
            <b-col cols="6" md="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.twitter_link') }}</label>
                <b-input
                  v-model="candidateData.twitter_link"
                  type="url"
                  :placeholder="$t('admin.candidate.form.twitter_link')"
                />
                <small
                  v-if="errors.twitter_link?.length > 0"
                  class="text-danger"
                >
                  {{ errors.twitter_link[0] }}
                </small>
              </b-form-group>
            </b-col>
          </b-row>
        </b-card>
      </b-col>

      <b-col cols="6">
        <b-card :title="$t('admin.candidate.form.groups.professional')" border-variant="primary">
          <b-row>
            <b-col cols="6" md="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.skills') }}</label>
                <v-select
                  v-model="candidateData.skills"
                  :placeholder="$t('admin.candidate.form.skills')"
                  :options="skills"
                  taggable
                  multiple
                  :close-on-select="false"
                  :create-option="(option) => vueSelectCreateOption(option, 'label')"
                  @option:created="createdSkillOption"
                  @search="(keyword, loading) => onSearchSkills(keyword, loading, 'skills')"
                />
                <small
                  v-if="errors.skills?.length > 0"
                  class="text-danger"
                >
                  {{ errors.skills[0] }}
                </small>
              </b-form-group>
            </b-col>
            <b-col cols="6" md="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.desired_job_role.label') }}</label>
                <v-select
                  v-model="candidateData.desired_job_roles"
                  :placeholder="$t('admin.candidate.form.desired_job_role.placeholder')"
                  :options="desiredJobRoles"
                  label="name"
                  value="id"
                  multiple
                  taggable
                  :close-on-select="false"
                  :create-option="vueSelectCreateOption"
                  @search="(keyword, loading) => onSearchJobRoles(keyword, loading, 'desiredJobRole')"
                />
                <small
                  v-if="errors.desired_job_roles?.length > 0"
                  class="text-danger"
                >
                  {{ errors.desired_job_roles[0] }}
                </small>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col cols="6" md="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.want_learn_skills') }}</label>
                <v-select
                  v-model="candidateData.want_learn_skills"
                  :placeholder="$t('admin.candidate.form.want_learn_skills')"
                  :options="wantLearnSkills"
                  taggable
                  multiple
                  :close-on-select="false"
                  :create-option="(option) => vueSelectCreateOption(option, 'label')"
                  @search="(keyword, loading) => onSearchSkills(keyword, loading, 'wantLearnSkills')"
                />
                <small
                  v-if="errors.want_learn_skills?.length > 0"
                  class="text-danger"
                >
                  {{ errors.want_learn_skills[0] }}
                </small>
              </b-form-group>
            </b-col>
            <b-col cols="6" md="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.next_promotion_job_role.label') }}</label>
                <v-select
                  v-model="candidateData.next_promotion_job_roles"
                  :placeholder="$t('admin.candidate.form.next_promotion_job_role.placeholder')"
                  :options="nextPromotionJobRole"
                  label="name"
                  value="id"
                  multiple
                  taggable
                  :close-on-select="false"
                  :create-option="vueSelectCreateOption"
                  @search="(keyword, loading) => onSearchJobRoles(keyword, loading, 'nextPromotionJobRole')"
                />
                <small
                  v-if="errors.next_promotion_job_roles?.length > 0"
                  class="text-danger"
                >
                  {{ errors.next_promotion_job_roles[0] }}
                </small>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col cols="6" md="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.want_work_with_tools') }}</label>
                <v-select
                  v-model="candidateData.want_work_with_tools"
                  :placeholder="$t('admin.candidate.form.want_work_with_tools')"
                  :options="wantWorkWithTools"
                  taggable
                  multiple
                  :close-on-select="false"
                  :create-option="(option) => vueSelectCreateOption(option, 'label')"
                  @search="(keyword, loading) => onSearchSkills(keyword, loading, 'wantWorkWithTools')"
                />
                <small
                  v-if="errors.want_work_with_tools?.length > 0"
                  class="text-danger"
                >
                  {{ errors.want_work_with_tools[0] }}
                </small>
              </b-form-group>
            </b-col>
            <b-col cols="6" md="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.current_job_role.label') }}</label>
                <v-select
                  v-model="candidateData.current_job_roles"
                  :placeholder="$t('admin.candidate.form.current_job_role.placeholder')"
                  :options="currentJobRoles"
                  label="name"
                  value="id"
                  multiple
                  taggable
                  :close-on-select="false"
                  :create-option="vueSelectCreateOption"
                  @search="(keyword, loading) => onSearchJobRoles(keyword, loading, 'currentJobRole')"
                />
                <small
                  v-if="errors.current_job_roles?.length > 0"
                  class="text-danger"
                >
                  {{ errors.current_job_roles[0] }}
                </small>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col cols="6" md="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.television_show') }}</label>
                <v-select
                  v-model="candidateData.television_shows"
                  :placeholder="$t('admin.candidate.form.television_show')"
                  :options="televisionShows"
                  label="name"
                  value="id"
                  :close-on-select="false"
                  multiple
                  taggable
                  :create-option="vueSelectCreateOption"
                  @search="onSearchTelevisionShows"
                />
                <small
                  v-if="errors.television_shows?.length > 0"
                  class="text-danger"
                >
                  {{ errors.television_shows[0] }}
                </small>
              </b-form-group>
            </b-col>
            <b-col cols="6" md="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.company') }}</label>
                <v-select
                  v-model="candidateData.company"
                  :placeholder="$t('admin.candidate.form.company')"
                  label="name"
                  value="id"
                  taggable
                  :options="companies"
                  :create-option="vueSelectCreateOption"
                  @search="onSearchCompanies"
                />
                <small
                  v-if="errors.company?.length > 0"
                  class="text-danger"
                >
                  {{ errors.company[0] }}
                </small>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col cols="6" md="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.budget_of_biggest_show') }}</label>
                <v-select
                  v-model="candidateData.budget_of_biggest_show"
                  :placeholder="$t('admin.candidate.form.budget_of_biggest_show')"
                  :options="budgetOfBiggestShowValues"
                />
                <small
                  v-if="errors.budget_of_biggest_show?.length > 0"
                  class="text-danger"
                >
                  {{ errors.budget_of_biggest_show[0] }}
                </small>
              </b-form-group>
            </b-col>
            <b-col cols="6" md="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.preferred_sectors') }}</label>
                <v-select
                  v-model="candidateData.preferred_sectors"
                  :placeholder="$t('admin.candidate.form.preferred_sectors')"
                  label="name"
                  value="id"
                  taggable
                  multiple
                  :close-on-select="false"
                  :create-option="vueSelectCreateOption"
                  :options="preferredSectorValues"
                />
                <small
                  v-if="errors.preferred_sectors?.length > 0"
                  class="text-danger"
                >
                  {{ errors.preferred_sectors[0] }}
                </small>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col cols="6" md="6">
              <b-form-group class="mt-1">
                <label>{{ $t('admin.candidate.form.commercial_experience') }}</label>
                <b-form-input
                  v-model="candidateData.commercial_experience"
                  type="range"
                  :min="commercialExperience.minYear"
                  :max="commercialExperience.maxYear"
                  step="1"
                >
                  <small
                    v-if="errors.commercial_experience?.length > 0"
                    class="text-danger"
                  >
                    {{ errors.commercial_experience[0] }}
                  </small>
                </b-form-input>
              </b-form-group>
            </b-col>
            <b-col cols="6" md="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.preferred_work_environments') }}</label>
                <v-select
                  v-model="candidateData.preferred_work_environments"
                  :placeholder="$t('admin.candidate.form.preferred_work_environments')"
                  :options="preferredWorkEnvironments"
                  label="name"
                  value="id"
                  multiple
                  taggable
                  :close-on-select="false"
                  :create-option="vueSelectCreateOption"
                  @search="(keyword, loading) => onSearchPreferredWorkEnvironments(keyword, loading)"
                />
                <small
                  v-if="errors.preferred_work_environments?.length > 0"
                  class="text-danger"
                >
                  {{ errors.preferred_work_environments[0] }}
                </small>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col cols="6" md="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.commercial_experience') }}</label>
                <v-select
                  v-model="candidateData.commercial_experience"
                  :placeholder="$t('admin.candidate.form.commercial_experience')"
                  :options="commercialExperience.values"
                />
                <small
                  v-if="errors.commercial_experience?.length > 0"
                  class="text-danger"
                >
                  {{ errors.commercial_experience[0] }}
                </small>
              </b-form-group>
            </b-col>
            <b-col cols="6" md="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.travel_availability.title') }}</label>
                <b-form-checkbox
                  v-model="candidateData.travel_availability"
                  :value="true"
                  :unchecked-value="false"
                >
                  {{ $t('admin.candidate.form.travel_availability.status') }}
                </b-form-checkbox>
                <small
                  v-if="errors.travel_availability?.length > 0"
                  class="text-danger"
                >
                  {{ errors.travel_availability[0] }}
                </small>
              </b-form-group>
            </b-col>
          </b-row>
        </b-card>

        <b-card :title="$t('admin.candidate.form.groups.remuneration')" border-variant="primary">
          <b-row>
            <b-col cols="6" md="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.gross_annual_salary') }}</label>
                <b-input
                  v-model="candidateData.gross_annual_salary"
                  type="number"
                  :placeholder="$t('admin.candidate.form.gross_annual_salary')"
                  required
                />
                <small
                  v-if="errors.gross_annual_salary?.length > 0"
                  class="text-danger"
                >
                  {{ errors.gross_annual_salary[0] }}
                </small>
              </b-form-group>
            </b-col>
            <b-col cols="6" md="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.week_rate') }}</label>
                <b-input
                  v-model="candidateData.week_rate"
                  type="number"
                  :placeholder="$t('admin.candidate.form.week_rate')"
                  required
                />
                <small
                  v-if="errors.week_rate?.length > 0"
                  class="text-danger"
                >
                  {{ errors.week_rate[0] }}
                </small>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col cols="6" md="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.day_rate') }}</label>
                <b-input
                  v-model="candidateData.day_rate"
                  type="number"
                  :placeholder="$t('admin.candidate.form.day_rate')"
                  required
                />
                <small
                  v-if="errors.day_rate?.length > 0"
                  class="text-danger"
                >
                  {{ errors.day_rate[0] }}
                </small>
              </b-form-group>
            </b-col>
            <b-col cols="6" md="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.salary_rate_currency') }}</label>
                <v-select
                  v-model="candidateData.salary_rate_currency"
                  :placeholder="$t('admin.candidate.form.salary_rate_currency')"
                  :options="salaryRateCurrencyValues"
                />
                <small
                  v-if="errors.salary_rate_currency?.length > 0"
                  class="text-danger"
                >
                  {{ errors.salary_rate_currency[0] }}
                </small>
              </b-form-group>
            </b-col>
          </b-row>
        </b-card>

        <b-card
          :title="$t('admin.candidate.form.groups.additional_notes')"
          border-variant="primary"
        >
          <b-row>
            <b-col cols="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.would_like_work_on') }}</label>
                <b-form-textarea
                  v-model="candidateData.would_like_work_on"
                  type="text"
                  :placeholder="$t('admin.candidate.form.would_like_work_on')"
                  rows="3"
                >
                  <small
                    v-if="errors.would_like_work_on?.length > 0"
                    class="text-danger"
                  >
                    {{ errors.would_like_work_on[0] }}
                  </small>
                </b-form-textarea>
              </b-form-group>
            </b-col>
            <b-col cols="6">
              <b-form-group>
                <label>{{ $t('admin.candidate.form.vfx_notes') }}</label>
                <b-form-textarea
                  v-model="candidateData.vfx_notes"
                  type="text"
                  :placeholder="$t('admin.candidate.form.vfx_notes')"
                  rows="3"
                >
                  <small
                    v-if="errors.vfx_notes?.length > 0"
                    class="text-danger"
                  >
                    {{ errors.vfx_notes[0] }}
                  </small>
                </b-form-textarea>
              </b-form-group>
            </b-col>
          </b-row>
        </b-card>
      </b-col>
    </b-row>
  </b-form>
</template>

<script>
import cloneDeep from 'lodash/cloneDeep';
import budgetOfBiggestShowValues from '@common/js/constants/candidateBudgetOfBiggestShowConstants';
import salaryRateCurrencyValues from '@common/js/constants/candidateSalaryRateCurrencyConstants';
import hasCompanyOption from '@common/js/mixins/hasCompanyOption';
import hasFileInput from '@common/js/mixins/hasFileInput';
import hasJobRoleOption from '@common/js/mixins/hasJobRoleOption';
import hasLocationOptions from '@common/js/mixins/hasLocationOptions';
import hasPreferredLocationOption from '@common/js/mixins/hasPreferredLocationOption';
import hasPreferredWorkEnvironmentOption from '@common/js/mixins/hasPreferredWorkEnvironmentOption';
import hasPreparedCandidateData from '@common/js/mixins/hasPreparedCandidateData';
import hasSkillOption from '@common/js/mixins/hasSkillOption';
import hasTelevisionShowOption from '@common/js/mixins/hasTelevisionShowOption';
import KeySkillLevelModal from '@admin/js/components/candidate/KeySkillLevelModal';

export default {
  components: {
    KeySkillLevelModal,
  },

  mixins: [
    hasCompanyOption,
    hasFileInput,
    hasJobRoleOption,
    hasLocationOptions,
    hasPreferredLocationOption,
    hasPreferredWorkEnvironmentOption,
    hasPreparedCandidateData,
    hasSkillOption,
    hasTelevisionShowOption,
  ],

  props: {
    candidate: {
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
      budgetOfBiggestShowValues: Object.values(budgetOfBiggestShowValues),
      countryValues: Object.freeze(window[window.globalSettingsKey].countries),
      timezoneValues: Object.freeze(window[window.globalSettingsKey].timezones),
      preferredSectorValues: Object.freeze(window[window.globalSettingsKey].preferredSectors),
      salaryRateCurrencyValues: Object.values(salaryRateCurrencyValues),
      commercialExperience: {
        values: Object.freeze(window[window.globalSettingsKey].commercialExperiences.values),
        maxYear: Object.freeze(window[window.globalSettingsKey].commercialExperiences.maxYear),
        minYear: Object.freeze(window[window.globalSettingsKey].commercialExperiences.minYear),
      },
      calendarOptions: {
        minDate: new Date(),
        dateFormatOptions: {
          year: 'numeric',
          month: 'numeric',
          day: 'numeric',
        },
        startWeekday: 1,
        locale: 'en-GB',
      },
      createdKeySkill: {},
      candidateData: cloneDeep(this.candidate),
      candidateDefaultPicturePath: Object.freeze(
        window[window.globalSettingsKey].candidateDefaultPicturePath,
      ),
      candidatePicture: null,
      user: window[window.globalSettingsKey].user,
    };
  },

  watch: {
    async 'candidateData.country'(value, oldValue) {
      await this.watchCountryChange(value, oldValue, 'candidateData');
    },

    'candidateData.region'(value, oldValue) {
      this.watchRegionChange(value, oldValue, 'candidateData');
    },

    'candidateData.city'(value, oldValue) {
      if (value !== oldValue && null !== value?.timezone) {
        this.candidateData.timezone = value.timezone;
      }
    },
  },

  async created() {
    this.$overlay.show();

    this.setCandidatePicture();

    try {
      await this.getLocationOptions('candidateData');
    } catch (e) {
      console.error(e);
    } finally {
      this.$overlay.hide();
    }
  },

  methods: {
    submitForm() {
      this.$emit('submit', this.getPreparedCandidate(this.candidateData));
    },

    onFileChange($event) {
      this.candidateData['picture'] = $event.target.files[0];
    },

    setSkillLevel(level) {
      const index = this.candidateData.skills.findIndex(
        (skill) => skill.label === this.createdKeySkill.label,
      );

      this.$set(
        this.candidateData.skills,
        index,
        Object.assign(
          {},
          this.candidateData.skills[index],
          {
            label: `${this.createdKeySkill.label} ${level.label}`,
            level: level.value,
            title: this.createdKeySkill.label,
          },
        ),
      );

      this.createdKeySkill = {};
    },

    vueSelectCreateOption(option, label = 'name') {
      return { [label]: option };
    },

    createdSkillOption(input) {
      this.createdKeySkill = input;

      this.$bvModal.show('modal-key-skill-level');
    },

    setCandidatePicture() {
      this.candidatePicture = this.candidateData.picture;

      this.candidateData.picture = null;
    },

    setSkillCircles(skillCircles) {
      this.candidateData.skill_circles = skillCircles;
    },
  },
};
</script>
