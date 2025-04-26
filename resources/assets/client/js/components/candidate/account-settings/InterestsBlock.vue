<template>
  <div id="account-setting-interests-block">
    <template v-if="editableBlock !== 'interests'">
      <b-card
        v-if="
          null !== candidateItem.would_like_work_on ||
          candidateItem.want_work_with_tools?.length > 0 ||
          candidateItem.want_learn_skills?.length > 0 ||
          candidateItem.desired_job_roles?.length > 0 ||
          candidateItem.next_promotion_job_roles?.length > 0 ||
          candidateItem.preferred_sectors?.length > 0 ||
          candidateItem.preferred_work_environments?.length > 0 ||
          candidateItem.preferred_locations?.length > 0
        "
        class="mt-2 text-medium"
      >
        <b-row v-if="candidateItem.would_like_work_on" class="mb-4">
          <b-col cols="12">
            <p class="mb-3">
              <b>
                {{ $t('client.account-settings.would_like_work_on.label') }}
              </b>
            </p>
            <show-more-less-clamp>
              {{ candidateItem.would_like_work_on }}
            </show-more-less-clamp>
          </b-col>
        </b-row>
        <b-row v-if="candidateItem.want_work_with_tools?.length > 0" class="mb-4">
          <b-col cols="12">
            <p class="mb-3">
              <b>
                {{ $t('client.account-settings.want_work_with_tools.label') }}
              </b>
            </p>
            <ul
              v-for="wantWorkWithTool in candidateItem.want_work_with_tools"
              :key="wantWorkWithTool.value"
            >
              <li>
                {{ `- ${wantWorkWithTool.label}` }}
              </li>
            </ul>
          </b-col>
        </b-row>
        <b-row v-if="candidateItem.want_learn_skills?.length > 0" class="mb-4">
          <b-col cols="12">
            <p class="mb-3">
              <b>
                {{ $t('client.account-settings.want_learn_skills.label') }}
              </b>
            </p>
            <ul v-for="learnSkill in candidateItem.want_learn_skills" :key="learnSkill.value">
              <li>
                {{ `- ${learnSkill.label}` }}
              </li>
            </ul>
          </b-col>
        </b-row>
        <b-row v-if="candidateItem.desired_job_roles?.length > 0" class="mb-4">
          <b-col cols="12">
            <p class="mb-3">
              <b>
                {{ $t('client.account-settings.desired_job_roles.label') }}
              </b>
            </p>
            <ul
              v-for="desiredJobRole in candidateItem.desired_job_roles"
              :key="desiredJobRole.id"
            >
              <li>
                {{ `- ${desiredJobRole.name}` }}
              </li>
            </ul>
          </b-col>
        </b-row>
        <b-row v-if="candidateItem.next_promotion_job_roles?.length > 0" class="mb-4">
          <b-col cols="12">
            <p class="mb-3">
              <b>
                {{ $t('client.account-settings.next_promotion_job_roles.label') }}
              </b>
            </p>
            <ul
              v-for="nextPromotionJobRole in candidateItem.next_promotion_job_roles"
              :key="nextPromotionJobRole.id"
            >
              <li>
                {{ `- ${nextPromotionJobRole.name}` }}
              </li>
            </ul>
          </b-col>
        </b-row>
        <b-row v-if="candidateItem.preferred_sectors?.length > 0" class="mb-4">
          <b-col cols="12">
            <p class="mb-3">
              <b>
                {{ $t('client.account-settings.preferred_sectors.label') }}
              </b>
            </p>
            <ul
              v-for="preferred_sector in candidateItem.preferred_sectors"
              :key="preferred_sector.value"
            >
              <li>
                {{ `- ${preferred_sector.name}` }}
              </li>
            </ul>
          </b-col>
        </b-row>
        <b-row v-if="candidateItem.preferred_work_environments?.length > 0" class="mb-4">
          <b-col cols="12">
            <p class="mb-3">
              <b>
                {{ $t('client.account-settings.preferred_work_environments.label') }}
              </b>
            </p>
            <ul
              v-for="preferredWorkEnvironments in candidateItem.preferred_work_environments"
              :key="preferredWorkEnvironments.id"
            >
              <li>
                {{ `- ${preferredWorkEnvironments.name}` }}
              </li>
            </ul>
          </b-col>
        </b-row>
        <b-row v-if="candidateItem.preferred_locations?.length > 0" class="mb-4">
          <b-col cols="12">
            <p class="mb-3">
              <b>
                {{ $t('client.account-settings.preferred_location.label') }}
              </b>
            </p>
            <ul
              v-for="preferredLocation in candidateItem.preferred_locations"
              :key="preferredLocation.id"
            >
              <li>
                {{ `- ${preferredLocation.name}` }}
              </li>
            </ul>
          </b-col>
        </b-row>
        <b-row>
          <b-col sm="4" class="mb-2 mb-sm-0">
            <p>
              <b>
                {{ $t('client.account-settings.travel_availability.title') }}
              </b>
              {{
                $t(`client.account-settings.travel_availability.${candidateItem.travel_availability ? 'yes' : 'no'}`)
              }}
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
                  {{ $t('client.account-settings.would_like_work_on.label') }}
                </b>
              </p>
            </b-col>
            <b-col sm="8">
              <b-form-group>
                <b-form-textarea
                  v-model="candidateItem.would_like_work_on"
                  type="text"
                  :placeholder="$t('client.account-settings.would_like_work_on.placeholder')"
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
          </b-row>
          <b-row class="mt-4 align-items-center">
            <b-col sm="4" class="mb-2 mb-sm-0">
              <p>
                <b>
                  {{ $t('client.account-settings.want_work_with_tools.label') }}
                </b>
              </p>
            </b-col>
            <b-col sm="8">
              <v-select
                v-model="candidateItem.want_work_with_tools"
                :placeholder="$t('client.account-settings.would_like_work_on.placeholder')"
                :options="wantWorkWithTools"
                multiple
                :close-on-select="false"
                @search="(keyword, loading) => onSearchSkills(keyword, loading, 'wantWorkWithTools')"
              />
              <small
                v-if="errors.want_work_with_tools?.length > 0"
                class="text-danger"
              >
                {{ errors.want_work_with_tools[0] }}
              </small>
            </b-col>
          </b-row>
          <b-row class="mt-4 align-items-center">
            <b-col sm="4" class="mb-2 mb-sm-0">
              <p>
                <b>
                  {{ $t('client.account-settings.want_learn_skills.label') }}
                </b>
              </p>
            </b-col>
            <b-col sm="8">
              <v-select
                v-model="candidateItem.want_learn_skills"
                :placeholder="$t('client.account-settings.want_learn_skills.placeholder')"
                :options="wantLearnSkills"
                multiple
                :close-on-select="false"
                @search="(keyword, loading) => onSearchSkills(keyword, loading, 'wantLearnSkills')"
              />
              <small
                v-if="errors.want_learn_skills?.length > 0"
                class="text-danger"
              >
                {{ errors.want_learn_skills[0] }}
              </small>
            </b-col>
          </b-row>
          <b-row class="mt-4 align-items-center">
            <b-col sm="4" class="mb-2 mb-sm-0">
              <p>
                <b>
                  {{ $t('client.account-settings.desired_job_roles.label') }}
                </b>
              </p>
            </b-col>
            <b-col sm="8">
              <v-select
                v-model="candidateItem.desired_job_roles"
                :placeholder="$t('client.account-settings.desired_job_roles.placeholder')"
                :options="desiredJobRoles"
                label="name"
                value="id"
                multiple
                :close-on-select="false"
                @search="(keyword, loading) => onSearchJobRoles(keyword, loading, 'desiredJobRole')"
              />
              <small
                v-if="errors.desired_job_roles?.length > 0"
                class="text-danger"
              >
                {{ errors.desired_job_roles[0] }}
              </small>
            </b-col>
          </b-row>
          <b-row class="mt-4 align-items-center">
            <b-col sm="4" class="mb-2 mb-sm-0">
              <p>
                <b>
                  {{ $t('client.account-settings.next_promotion_job_roles.label') }}
                </b>
              </p>
            </b-col>
            <b-col sm="8">
              <v-select
                v-model="candidateItem.next_promotion_job_roles"
                :placeholder="$t('client.account-settings.next_promotion_job_roles.placeholder')"
                :options="nextPromotionJobRole"
                label="name"
                value="id"
                multiple
                :close-on-select="false"
                @search="(keyword, loading) => onSearchJobRoles(keyword, loading, 'nextPromotionJobRole')"
              />
              <small
                v-if="errors.next_promotion_job_roles?.length > 0"
                class="text-danger"
              >
                {{ errors.next_promotion_job_roles[0] }}
              </small>
            </b-col>
          </b-row>
          <b-row class="mt-4 align-items-center">
            <b-col sm="4" class="mb-2 mb-sm-0">
              <p>
                <b>
                  {{ $t('client.account-settings.preferred_sectors.label') }}
                </b>
              </p>
            </b-col>
            <b-col sm="8">
              <v-select
                v-model="candidateItem.preferred_sectors"
                :placeholder="$t('client.account-settings.preferred_sectors.placeholder')"
                label="name"
                value="id"
                multiple
                :close-on-select="false"
                :options="preferredSectorValues"
              />
              <small
                v-if="errors.preferred_sectors?.length > 0"
                class="text-danger"
              >
                {{ errors.preferred_sectors[0] }}
              </small>
            </b-col>
          </b-row>
          <b-row class="mt-4 align-items-center">
            <b-col sm="4" class="mb-2 mb-sm-0">
              <p>
                <b>
                  {{ $t('client.account-settings.preferred_work_environments.label') }}
                </b>
              </p>
            </b-col>
            <b-col sm="8">
              <v-select
                v-model="candidateItem.preferred_work_environments"
                :placeholder="$t('client.account-settings.preferred_work_environments.placeholder')"
                :options="preferredWorkEnvironments"
                label="name"
                value="id"
                multiple
                :close-on-select="false"
                @search="(keyword, loading) => onSearchPreferredWorkEnvironments(keyword, loading)"
              />
              <small
                v-if="errors.preferred_work_environments?.length > 0"
                class="text-danger"
              >
                {{ errors.preferred_work_environments[0] }}
              </small>
            </b-col>
          </b-row>
          <b-row class="mt-4 align-items-center">
            <b-col sm="4" class="mb-2 mb-sm-0">
              <p>
                <b>
                  {{ $t('client.account-settings.preferred_location.label') }}
                </b>
              </p>
            </b-col>
            <b-col sm="8">
              <v-select
                v-model="candidateItem.preferred_locations"
                :placeholder="$t('client.account-settings.preferred_location.placeholder')"
                :options="preferredLocations"
                label="name"
                value="id"
                multiple
                :close-on-select="false"
                @search="onSearchPreferredLocations"
              />
              <small
                v-if="errors.preferred_locations?.length > 0"
                class="text-danger"
              >
                {{ errors.preferred_locations[0] }}
              </small>
            </b-col>
          </b-row>
          <b-row class="mt-4 align-items-center">
            <b-col sm="4" class="mb-2 mb-sm-0">
              <p>
                <b>
                  {{ $t('client.account-settings.travel_availability.title') }}
                </b>
              </p>
            </b-col>
            <b-col sm="8">
              <b-form-checkbox
                v-model="candidateItem.travel_availability"
                :value="true"
                :unchecked-value="false"
              >
                {{ $t('client.account-settings.travel_availability.status') }}
              </b-form-checkbox>
              <small
                v-if="errors.travel_availability?.length > 0"
                class="text-danger"
              >
                {{ errors.travel_availability[0] }}
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
import hasJobRoleOption from '@common/js/mixins/hasJobRoleOption';
import hasPreferredLocationOption from '@common/js/mixins/hasPreferredLocationOption';
import hasPreferredWorkEnvironmentOption from '@common/js/mixins/hasPreferredWorkEnvironmentOption';
import hasSkillOption from '@common/js/mixins/hasSkillOption';
import ShowMoreLessClamp from '@common/js/components/ShowMoreLess/ShowMoreLessClamp';

export default {
  components: {
    ShowMoreLessClamp,
  },

  mixins: [
    hasJobRoleOption,
    hasPreferredLocationOption,
    hasPreferredWorkEnvironmentOption,
    hasSkillOption,
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
      preferredSectorValues: Object.freeze(window[window.globalSettingsKey].preferredSectors),
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
