<template>
  <div id="account-setting-general-block">
    <template v-if="editableBlock !== 'general'">
      <b-card
        v-if="
          candidateItem.skills?.length > 0 ||
          null !== candidateItem.commercial_experience ||
          !isEmpty(candidateItem.company) ||
          candidateItem.television_shows?.length > 0 ||
          null !== candidateItem.budget_of_biggest_show?.value
        "
        class="mt-2 text-medium"
      >
        <b-row v-if="candidateItem.skills?.length > 0" class="mb-4">
          <b-col cols="12">
            <div>
              <p class="mb-2">
                <b>{{ $t('client.account-settings.skills.label') }}</b>
              </p>
              <ul v-for="skill in candidateItem.skills" :key="skill.value">
                <li>
                  {{ `- ${skill.label}` }}
                </li>
              </ul>
            </div>
          </b-col>
        </b-row>
        <b-row v-if="!isEmpty(candidateItem.company)" class="mb-4">
          <b-col cols="12">
            <p class="mt-1">
              <b>
                {{ $t('client.account-settings.company.label') }}
              </b>
              {{ candidateItem.company.name }}
            </p>
          </b-col>
        </b-row>
        <b-row v-if="candidateItem.commercial_experience_years" class="mb-4">
          <b-col cols="12">
            <p class="mt-1">
              <b>
                {{ $t('client.account-settings.commercial_experience.label') }}
              </b>
              {{ candidateItem.commercial_experience_years }}
            </p>
          </b-col>
        </b-row>
        <b-row v-if="candidateItem.television_shows?.length > 0" class="mb-4">
          <b-col cols="12">
            <p class="mb-3">
              <b>
                {{ $t('client.account-settings.television_show.label') }}
              </b>
            </p>
            <ul
              v-for="televisionShow in candidateItem.television_shows"
              :key="televisionShow.id"
            >
              <li>
                {{ `- ${televisionShow.name}` }}
                {{ televisionShow.skill ? ` - ${televisionShow.skill}` : '' }}
              </li>
            </ul>
          </b-col>
        </b-row>
        <b-row v-if="candidateItem.budget_of_biggest_show?.value">
          <b-col cols="12">
            <p class="mb-3">
              <b>{{ $t('client.account-settings.budget_of_biggest_show.label') }}</b>
              {{ candidateItem.budget_of_biggest_show?.label }}
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
                <b>{{ $t('client.account-settings.skills.label') }}</b>
              </p>
            </b-col>
            <b-col sm="8">
              <v-select
                v-model="candidateItem.skills"
                :placeholder="$t('client.account-settings.skills.placeholder')"
                :options="skills"
                multiple
                :close-on-select="false"
                @search="(keyword, loading) => onSearchSkills(keyword, loading, 'skills')"
              />
              <small
                v-if="errors.skills?.length > 0"
                class="text-danger"
              >
                {{ errors.skills[0] }}
              </small>
            </b-col>
          </b-row>
          <b-row class="mt-4 align-items-center">
            <b-col sm="4" class="mb-2 mb-sm-0">
              <p>
                <b>{{ $t('client.account-settings.company.label') }}</b>
              </p>
            </b-col>
            <b-col sm="8">
              <v-select
                v-model="candidateItem.company"
                :placeholder="$t('client.account-settings.company.placeholder')"
                label="name"
                value="id"
                taggable
                :options="companies"
                @search="onSearchCompanies"
              />
              <small
                v-if="errors.company?.length > 0"
                class="text-danger"
              >
                {{ errors.company[0] }}
              </small>
            </b-col>
          </b-row>
          <b-row class="mt-4 align-items-center">
            <b-col sm="4" class="mb-2 mb-sm-0">
              <p>
                <b>
                  {{ $t('client.account-settings.commercial_experience.label') }}
                </b>
              </p>
            </b-col>
            <b-col sm="8">
              <v-select
                v-model="candidateItem.commercial_experience"
                :placeholder="$t('client.account-settings.commercial_experience.placeholder')"
                :options="commercialExperience.values"
              />
              <small
                v-if="errors.commercial_experience?.length > 0"
                class="text-danger"
              >
                {{ errors.commercial_experience[0] }}
              </small>
            </b-col>
          </b-row>
          <b-row class="mt-4 align-items-center">
            <b-col sm="4" class="mb-2 mb-sm-0">
              <p>
                <b>
                  {{ $t('client.account-settings.television_show.label') }}
                </b>
              </p>
            </b-col>
            <b-col sm="8">
              <v-select
                v-model="candidateItem.television_shows"
                :placeholder="$t('client.account-settings.television_show.placeholder')"
                :options="televisionShows"
                label="name"
                value="id"
                :close-on-select="false"
                multiple
                @search="onSearchTelevisionShows"
              />
              <small
                v-if="errors.television_shows?.length > 0"
                class="text-danger"
              >
                {{ errors.television_shows[0] }}
              </small>
            </b-col>
          </b-row>
          <b-row class="mt-4 align-items-center">
            <b-col sm="4" class="mb-2 mb-sm-0">
              <p>
                <b>
                  {{ $t('client.account-settings.budget_of_biggest_show.label') }}
                </b>
              </p>
            </b-col>
            <b-col sm="8">
              <b-form-group>
                <v-select
                  v-model="candidateItem.budget_of_biggest_show"
                  :placeholder="$t('client.account-settings.budget_of_biggest_show.placeholder')"
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
import hasCompanyOption from '@common/js/mixins/hasCompanyOption';
import budgetOfBiggestShowValues from '@common/js/constants/candidateBudgetOfBiggestShowConstants';
import hasSkillOption from '@common/js/mixins/hasSkillOption';
import hasTelevisionShowOption from '@common/js/mixins/hasTelevisionShowOption';

export default {
  mixins: [
    hasCompanyOption,
    hasSkillOption,
    hasTelevisionShowOption,
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
      budgetOfBiggestShowValues: Object.values(budgetOfBiggestShowValues),
      commercialExperience: {
        values: Object.freeze(window[window.globalSettingsKey].commercialExperiences.values),
        maxYear: Object.freeze(window[window.globalSettingsKey].commercialExperiences.maxYear),
        minYear: Object.freeze(window[window.globalSettingsKey].commercialExperiences.minYear),
      },
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
