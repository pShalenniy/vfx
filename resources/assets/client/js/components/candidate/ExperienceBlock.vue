<template>
  <div id="experience-block">
    <b-tabs>
      <b-tab
        v-if="!isEmpty(candidate.filmographies)"
        :title="$t('client.candidate.content_section.filmography')"
      >
        <div>
          <show-more-less-table-row
            :line-count="showMoreLineFilmographyCount"
            :see-less-text="$t('common.see_less')"
            :see-all-text="$t('common.see_all')"
          >
            <b-table
              class="position-relative"
              thead-class="d-none"
              :items="candidate.filmographies"
              :responsive="true"
              :fields="filmographyTableColumns"
              primary-key="id"
            >
              <template #cell(poster_url)="data">
                <img
                  :src="data.item.poster_url ?? '/images/client/no-media.svg'"
                  alt=""
                  height="55"
                  width="45"
                />
              </template>
              <template #cell(title)="data">
                <h5 class="h5">
                  <b>
                    {{ data.item.title }}
                  </b>
                </h5>
                <p class="mb-2">
                  {{ $t(`client.candidate.role_types.${data.item.role_type}`) }}
                </p>
              </template>
            </b-table>
          </show-more-less-table-row>
        </div>
      </b-tab>
      <b-tab
        v-if="!isEmpty(candidate.linkedin_experiences)"
        :title="$t('client.candidate.content_section.linkedin_info')"
      >
        <show-more-less-table-row
          :line-count="showMoreLineLinkedinCount"
          :see-less-text="$t('common.see_less')"
          :see-all-text="$t('common.see_all')"
        >
          <b-table
            class="position-relative"
            thead-class="d-none"
            :items="candidate.linkedin_experiences"
            :responsive="true"
            :fields="linkedinTableColumns"
            primary-key="id"
          >
            <template #cell(image)="data">
              <img
                :src="data.item.image ?? '/images/client/linkedin-no-image.jpg'"
                alt=""
              />
            </template>
            <template #cell(company)="data">
              <p v-if="data.item.company">
                <b>{{ data.item.company }}</b>
              </p>
              <p v-if="!isEmpty(data.item.working_period)">
                {{ getLinkedinWorkingPeriod(data.item.working_period) }}
              </p>
              <p v-if="data.item.employment" class="mt-2 mb-4">
                {{ data.item.employment }}
              </p>
              <template v-if="data.item.details?.length > 0">
                <div v-for="detail in data.item.details" :key="detail.id" class="mt-2">
                  <p v-if="detail.title">
                    {{ detail.title }}
                  </p>
                  <p v-if="detail.dates" class="mb-4">
                    {{ detail.dates }}
                  </p>
                </div>
                <hr />
              </template>
            </template>
          </b-table>
        </show-more-less-table-row>
      </b-tab>
      <b-tab
        v-if="
          !isEmpty(candidate.skills) ||
          !isEmpty(candidate.television_shows) ||
          candidate.company ||
          candidate.commercial_experience ||
          candidate.budget_of_biggest_show ||
          candidate.vfx_notes
        "
        :title="$t('client.candidate.content_section.general_info')"
      >
        <general-info-block class="mt-2" :candidate="candidate" />
      </b-tab>
    </b-tabs>
  </div>
</template>

<script>
import hasLinkedinExperience from '@client/js/mixins/hasLinkedinExperience';
import GeneralInfoBlock from '@client/js/components/candidate/GeneralInfoBlock';
import ShowMoreLessTableRow from '@common/js/components/ShowMoreLess/ShowMoreLessTableRow';

export default {
  components: {
    GeneralInfoBlock,
    ShowMoreLessTableRow,
  },

  mixins: [
    hasLinkedinExperience,
  ],

  props: {
    candidate: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      // According to design.
      showMoreLineFilmographyCount: 15,
      // According to design.
      showMoreLineLinkedinCount: 5,
      filmographyTableColumns: [
        { key: 'poster_url' },
        { key: 'title' },
        { key: 'year' },
      ],
      linkedinTableColumns: [
        { key: 'image' },
        { key: 'company' },
      ],
    };
  },
};
</script>
