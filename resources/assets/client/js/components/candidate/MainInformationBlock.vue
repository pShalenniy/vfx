<template>
  <div class="main-information-block">
    <div class="mb-4">
      <div class="candidate-profile">
        <div class="candidate-profile__image">
          <img v-if="candidate.picture" :src="candidate.picture" alt="" />
          <img v-else :src="candidateDefaultPicturePath" alt="" height="170" width="170" />
        </div>
        <div class="candidate-profile__col">
          <div class="candidate-profile__header bg-secondary text-white">
            <div>
              <p class="h5 text-big mb-1">
                <b>
                  {{ candidate.full_name }}
                </b>
              </p>
              <p v-if="candidate.current_job_roles?.length > 0" class="text-big">
                {{ getMultipleItemsInString(candidate.current_job_roles) }}
              </p>
              <div v-if="candidate.nationalities?.length > 0" class="mt-5">
                <p>
                  <b>
                    {{ $t('client.candidate.content_section.personal.nationalities') }}
                    {{ getMultipleItemsInString(candidate.nationalities) }}
                  </b>
                </p>
              </div>
              <div v-if="candidate.alternative_citizenship_residencies?.length > 0" class="mt-2">
                <p>
                  <b>
                    {{ getMultipleItemsInString(candidate.alternative_citizenship_residencies) }}
                    {{ $t('client.candidate.content_section.personal.citizenship') }}
                  </b>
                </p>
              </div>
              <div v-if="candidate.timezone" class="list-style-disc mt-2">
                <p>
                  <b>
                    {{ candidate.timezone.name }} - {{ candidate.timezone.offset }}
                    {{ $t('client.candidate.content_section.timezone.title') }}
                  </b>
                </p>
              </div>
              <div v-if="candidate.city || candidate.country" class=" list-style-disc mt-2">
                <p>
                  <b>
                    {{ $t('client.candidate.content_section.current_location') }}
                    {{ getCurrentLocation(candidate.country?.code, candidate.city?.name) }}
                  </b>
                </p>
              </div>
              <div v-if="candidate.available" class="list-style-disc mt-2 mb-4">
                <p>
                  <b>
                    {{ $t('client.candidate.content_section.personal.next_availability') }}
                    {{ candidate.available }}
                  </b>
                </p>
              </div>
              <button
                v-if="candidate.portfolio_url"
                v-b-modal:modal-candidate-portfolio
                class="btn btn-lg btn-primary text-blue"
              >
                {{ $t('client.candidate.content_section.portfolio') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="candidate-profile__card w-100 card p-4 p-lg-5 text-body">
      <div class="row">
        <div v-if="!isEmpty(candidate.linkedin_experiences)" class="col-sm-6 mb-3">
          <linkedin-block :candidate="candidate" />
        </div>
        <div v-if="!isEmpty(candidate.filmographies)" class="col-sm-4 mb-3">
          <i-m-d-b-block :candidate="candidate" />
        </div>
        <div
          v-if="
            !isEmpty(candidate.skills) ||
            !isEmpty(candidate.budget_of_biggest_show) ||
            candidate.company ||
            candidate.commercial_experience ||
            candidate.vfx_notes
          "
          class="mt-2 mb-2 col-sm-4"
        >
          <h4 class="h4 text-light-blue">
            {{ $t('client.candidate.content_section.experience') }}
          </h4>

          <general-info-block
            v-if="
              !isEmpty(candidate.skills) ||
              !isEmpty(candidate.budget_of_biggest_show) ||
              candidate.company ||
              candidate.commercial_experience ||
              candidate.vfx_notes
            "
            :candidate="candidate"
            class="mt-2"
          />
        </div>
      </div>

      <interests-block
        v-if="
          candidate.would_like_work_on ||
          !isEmpty(candidate.want_work_with_tools) ||
          !isEmpty(candidate.want_learn_skills) ||
          !isEmpty(candidate.desired_job_roles?.length) ||
          !isEmpty(candidate.next_promotion_job_roles) ||
          !isEmpty(candidate.preferred_sectors) ||
          !isEmpty(candidate.preferred_work_environments) ||
          !isEmpty(candidate.preferred_locations) ||
          candidate.travel_availability
        "
        :candidate="candidate"
        class="mt-3"
      />

      <div
        v-if="
          candidate.gross_annual_salary ||
          candidate.salary_rate_currency ||
          candidate.week_rate ||
          candidate.day_rate
        "
        class="mt-3"
      >
        <h4 class="h4 mb-3 text-light-blue">
          {{ $t('client.candidate.content_section.personal.title') }}
        </h4>

        <remuneration-block :candidate="candidate" class="mb-3" />
      </div>

      <div
        v-if="
          candidate.email ||
          candidate.phone_number ||
          candidate.instagram_link ||
          candidate.linkedin_link ||
          candidate.twitter_link
        "
        class="mt-3"
      >
        <h4 class="h4 mb-3 text-light-blue text-medium">
          {{ $t('client.candidate.content_section.contact_social_media.contact') }}
        </h4>

        <div class="row mb-2 text-medium">
          <div class="col-sm-4">
            <p class="mb-3 text-primary">
              <b>
                {{ $t('client.candidate.content_section.personal.email') }}
              </b>
            </p>
            <p class="mb-6">
              {{ candidate.email }}
            </p>
          </div>
          <div v-if="candidate.phone_number" class="col-sm-4">
            <p class="mb-6 text-primary mb-3">
              <b>
                {{ $t('client.candidate.content_section.personal.phone_number') }}
              </b>
            </p>
            <p class="mb-6">
              {{ candidate.phone_number }}
            </p>
          </div>
          <div class="col-sm-2 offset-2 mt-3">
            <candidate-social-media-block :candidate="candidate" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import hasLocationOptions from '@common/js/mixins/hasLocationOptions';
import hasMultipleItems from '@client/js/mixins/hasMultipleItems';
import CandidateSocialMediaBlock from '@client/js/components/common/CandidateSocialMediaBlock';
import GeneralInfoBlock from '@client/js/components/candidate/GeneralInfoBlock';
import LinkedinBlock from '@client/js/components/candidate/LinkedinBlock';
import IMDBBlock from '@client/js/components/candidate/IMDBBlock';
import InterestsBlock from '@client/js/components/candidate/InterestsBlock';
import RemunerationBlock from '@client/js/components/candidate/RemunerationBlock';

export default {
  components: {
    CandidateSocialMediaBlock,
    GeneralInfoBlock,
    IMDBBlock,
    InterestsBlock,
    LinkedinBlock,
    RemunerationBlock,
  },

  mixins: [
    hasLocationOptions,
    hasMultipleItems,
  ],

  props: {
    candidate: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      candidateDefaultPicturePath: window[window.globalSettingsKey].candidateDefaultPicturePath,
    };
  },
};
</script>
