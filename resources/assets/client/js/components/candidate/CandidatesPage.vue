<template>
  <div id="client-candidates-page">
    <create-shortlist-modal @created="addShortlist" />

    <section class="section section-search">
      <img class="section-search-bg" src="/images/client/search.jpg" alt="" />
      <div class="container">
        <div class="section-search-block">
          <b-form-group>
            <img
              src="/images/client/icons/search.png"
              height="25px"
              width="25px"
              alt=""
              class="section-search-block-icon"
            />
            <b-form-input
              v-model="tempFilters.keyword"
              :placeholder="$t('client.candidate.search.placeholder')"
              @keyup.enter.prevent="setKeyword"
            />
            <button
              type="submit"
              class="btn btn-primary text-light-blue section-search-button"
              @click.prevent="setKeyword"
            >
              {{ $t('client.candidate.search.button') }}
            </button>
          </b-form-group>
        </div>
      </div>
    </section>
    <section class="section section-filter bg-secondary overflow-visible">
      <div class="container filter-section text-blue">
        <div class="row">
          <div class="col-md-3">
            <v-select
              v-model="filters.country_id"
              :options="countries"
              :placeholder="$t('client.candidate.dropdowns.country')"
              label="name"
              value="id"
            />
          </div>
          <div class="col-md-3">
            <v-select
              v-model="filters.city_id"
              :options="cities"
              :placeholder="$t('client.candidate.dropdowns.city')"
              label="name"
              value="id"
              @search="onSearchCities"
            />
          </div>
          <div class="col-md-3">
            <v-select
              v-model="filters.timezone_id"
              :options="timezones"
              :placeholder="$t('client.candidate.dropdowns.timezone')"
              label="name"
              value="id"
            />
          </div>
          <div class="col-md-3 text-blue">
            <date-picker
              v-model="filters.next_availability"
              style="min-width: 170px; width: 100%;"
              :value-type="calendarOptions.format"
              :title-format="calendarOptions.format"
              :locale="calendarOptions.lang"
              range
              type="date"
              :placeholder="$t('client.candidate.dropdowns.next_availability')"
            />
          </div>
        </div>
        <div class="row mt-2">
          <div class="col-md-3">
            <v-select
              v-model="filters.skills"
              multiple
              :close-on-select="false"
              :placeholder="$t('client.candidate.dropdowns.skills')"
              :options="skills"
              @search="(keyword, loading) => onSearchSkills(keyword, loading, 'skills')"
            />
          </div>
          <div class="col-md-3">
            <v-select
              v-model="filters.television_shows"
              label="name"
              value="id"
              multiple
              :placeholder="$t('client.candidate.dropdowns.television_shows')"
              :options="televisionShows"
              @search="onSearchTelevisionShows"
            />
          </div>
          <div class="col-md-3">
            <v-select
              v-model="filters.commercial_experience"
              label="name"
              value="value"
              :placeholder="$t('client.candidate.dropdowns.commercial_experience')"
              :options="commercialExperienceValues"
            />
          </div>
          <div class="col-md-3">
            <v-select
              v-model="filters.company_id"
              label="name"
              value="id"
              :placeholder="$t('client.candidate.dropdowns.company')"
              :options="companies"
              @search="onSearchCompanies"
            />
          </div>
        </div>
        <div class="row mt-2">
          <div class="col-md-3">
            <v-select
              v-model="filters.current_job_role_id"
              :placeholder="$t('client.candidate.dropdowns.current_job_role')"
              :options="currentJobRoles"
              label="name"
              value="id"
              @search="(keyword, loading) => onSearchJobRoles(keyword, loading, 'currentJobRole')"
            />
          </div>
          <div class="col-md-3">
            <v-select
              v-model="filters.desired_job_role_id"
              :placeholder="$t('client.candidate.dropdowns.desired_job_role')"
              :options="desiredJobRoles"
              label="name"
              value="id"
              @search="(keyword, loading) => onSearchJobRoles(keyword, loading, 'desiredJobRole')"
            />
          </div>
          <div class="col-md-3">
            <v-select
              v-model="filters.preferred_location_id"
              :placeholder="$t('client.candidate.dropdowns.preferred_location')"
              :options="preferredLocations"
              label="name"
              value="id"
              @search="onSearchPreferredLocations"
            />
          </div>
          <div class="col-md-3">
            <v-select
              v-model="filters.shortlist"
              label="title"
              value="id"
              :placeholder="$t('client.candidate.dropdowns.shortlist')"
              :options="shortlists"
            />
          </div>
        </div>
      </div>
    </section>
    <section class="section section-candidates-list">
      <div class="container text-blue">
        <div v-if="!isStarred">
          <h1 class="h1 section-title">
            {{ $t('client.candidate.new_star_candidates') }}
          </h1>
          <p class="mb-2">
            {{ $tc('client.candidate.candidates', meta.total) }}
          </p>
          <b-tabs pills>
            <b-tab
              v-for="department in departments"
              :key="department.id"
              :title="department.name"
              @click="changeDepartmentFilter(department.id)"
            />
          </b-tabs>
        </div>
        <h1 v-else class="h1 section-title text-light-blue">
          {{ $t('client.candidate.star_candidates') }}
        </h1>
        <div v-for="candidate in candidates" :key="candidate.id" class="candidate-list-item">
          <div v-if="isStarred" class="candidate-list-item__image">
            <img
              src="/images/client/icons/star-candidate.svg"
              height="45px"
              width="40px"
              alt=""
            />
          </div>
          <div class="candidate-list-item__body">
            <div class="candidate-list-item__info">
              <p><b>{{ candidate.full_name }}</b></p>
              <p>
                {{
                  candidate.country?.name
                    ? `${$t('client.candidate.available_for')} ${candidate.country.name} / `
                    : ''
                }}
                {{
                  !isEmpty(candidate.nationalities)
                    ? `${$t('client.candidate.nationalities')} ${getItemsList(candidate.nationalities)} / `
                    : ''
                }}
                {{
                  !isEmpty(candidate.current_job_roles)
                    ? `${$t('client.candidate.current_job_roles')} ${getItemsList(candidate.current_job_roles)} / `
                    : ''
                }}
                {{
                  candidate.next_availability
                    ? `${$t('client.candidate.available')} ${candidate.next_availability}`
                    : ''
                }}
              </p>
            </div>
            <div>
              <a :href="route('candidate.show', { candidateSlug: candidate.slug })">
                {{ $t('client.candidate.see_experience') }}
              </a>
            </div>
          </div>
        </div>
        <b-pagination
          v-model="meta.currentPage"
          :total-rows="meta.total"
          :per-page="meta.perPage"
          first-number
          last-number
          prev-class="prev-item"
          next-class="next-item"
        >
          <template #next-text>
            {{ $t('client.candidate.next_page') }} >>
          </template>
        </b-pagination>
      </div>
    </section>
  </div>
</template>

<script>
import cloneDeep from 'lodash/cloneDeep';
import debounce from 'lodash/debounce';
import first from 'lodash/first';
import DatePicker from 'vue2-datepicker';
import deepFreeze from '@common/js/helpers/deepFreeze';
import hasCompanyOption from '@common/js/mixins/hasCompanyOption';
import hasJobRoleOption from '@common/js/mixins/hasJobRoleOption';
import hasPreferredLocationOption from '@common/js/mixins/hasPreferredLocationOption';
import hasPagination from '@common/js/mixins/hasPagination';
import hasSkillOption from '@common/js/mixins/hasSkillOption';
import hasTelevisionShowOption from '@common/js/mixins/hasTelevisionShowOption';
import CreateShortlistModal from '@client/js/components/candidate/CreateShortlistModal';

export default {
  components: {
    CreateShortlistModal,
    DatePicker,
  },

  mixins: [
    hasCompanyOption,
    hasJobRoleOption,
    hasPagination,
    hasPreferredLocationOption,
    hasTelevisionShowOption,
    hasSkillOption,
  ],

  data() {
    return {
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
      getDataMethod: 'getCandidates',
      shortlists: Object.freeze(window[window.globalSettingsKey].shortlists),
      candidates: [],
      cities: Object.freeze(window[window.globalSettingsKey].cities),
      commercialExperienceValues: Object.values(
        deepFreeze(window[window.globalSettingsKey].commercialExperienceValues),
      ),
      countries: Object.freeze(window[window.globalSettingsKey].countries),
      departments: Object.freeze(window[window.globalSettingsKey].departments),
      timezones: Object.freeze(window[window.globalSettingsKey].timezones),
      tempFilters: {
        keyword: null,
      },
      filters: {
        city_id: null,
        company_id: null,
        commercial_experience: null,
        country_id: null,
        current_job_role_id: null,
        department: null,
        desired_job_role_id: null,
        keyword: null,
        next_availability: [],
        preferred_location_id: null,
        shortlist: null,
        skills: [],
        television_shows: [],
        timezone_id: null,
      },
    };
  },

  computed: {
    isStarred() {
      return !this.filters.city_id &&
        !this.filters.company_id &&
        !this.filters.commercial_experience &&
        !this.filters.country_id &&
        !this.filters.current_job_role_id &&
        !this.filters.department &&
        !this.filters.desired_job_role_id &&
        !this.filters.keyword &&
        this.filters.next_availability.length < 1 &&
        !this.filters.preferred_location_id &&
        !this.filters.shortlist &&
        this.filters.skills.length < 1  &&
        this.filters.television_shows.length < 1 &&
        !this.filters.timezone_id;
    },
  },

  watch: {
    'meta.currentPage'(newValue, oldValue) {
      if (newValue !== oldValue) {
        this.getCandidates();
      }
    },
    'filters.next_availability'(newValue, oldValue) {
      if (newValue?.includes(null)) {
        this.filters.next_availability = [];
      }
    },
  },

  async created() {
    await this.getCandidates();

    this.fillData();
  },

  methods: {
    async getCandidates() {
      this.$overlay.show();

      if (this.isEmpty(this.getFilterParameters())) {
        this.isStarred = true;
      } else {
        const params = Object.assign(
          {},
          { page: this.meta.currentPage },
          { sort: { by: 'next_availability', direction: 'asc' } },
          this.getFilterParameters(),
        );

        try {
          const { data } = await axios.get(route('candidate.list'), { params });

          if (data.data) {
            this.candidates = data.data;
          }

          this.fillMeta(data);
        } catch (e) {
          console.error(e);
        } finally {
          this.$overlay.hide();
        }
      }
    },

    getItemsList(items) {
      const values = [];

      items.forEach((item) => {
        values.push(item.name);
      });

      return values.join(', ');
    },

    onSearchCities(keyword, loading) {
      if (!keyword.length) {
        return;
      }

      loading(true);

      this.searchCities(keyword, loading, this);
    },

    searchCities: debounce(async (keyword, loading, vm) => {
      try {
        const { data } = await axios.get(route('common.city.search'), { params: { keyword } });

        vm.cities = data.data;
      } catch (e) {
        vm.$notify.errors(e);
      } finally {
        loading(false);
      }
    }, 700),

    changeDepartmentFilter(value) {
      this.filters.department = value;
    },

    getFilterParameters() {
      const filters = cloneDeep(this.filters);

      if (this.filters.shortlist?.id === 'create_another_list') {
        this.$bvModal.show('modal-shortlist-create');

        this.filters.shortlist = null;

        return;
      }

      const numberFields = [
        'city_id',
        'company_id',
        'country_id',
        'current_job_role_id',
        'desired_job_role_id',
        'preferred_location_id',
        'shortlist',
        'timezone_id',
      ];

      for (const numberField of numberFields) {
        if (filters[numberField] && filters[numberField]?.id) {
          delete filters[numberField];

          filters[numberField] = this.filters[numberField].id;
        }
      }

      if (null !== filters.commercial_experience && null !== filters.commercial_experience?.value) {
        delete filters.commercial_experience;

        filters.commercial_experience = this.filters.commercial_experience.value;
      }

      const multipleFields = [
        'skills',
        'television_shows',
      ];

      for (const multipleField of multipleFields) {
        delete filters[multipleField];

        const data = [];

        if (multipleField === 'television_shows') {
          this.filters[multipleField].forEach((item) => {
            data.push(item.imdb_id);
          });
        } else {
          this.filters[multipleField].forEach((item) => {
            data.push({ id: Number(item.value), level: item.level });
          });
        }

        filters[multipleField] = data;
      }

      if (
        (
          false === this.isStarred &&
          null === filters.department
        ) &&
        (
          null !== filters.city_id ||
          null !== filters.company_id ||
          null !== filters.commercial_experience ||
          null !== filters.country_id ||
          null !== filters.current_job_role_id ||
          null !== filters.desired_job_role_id ||
          null !== filters.keyword ||
          filters.next_availability.length < 1 ||
          null !== filters.preferred_location_id ||
          null !== filters.shortlist ||
          filters.skills.length < 1  ||
          filters.television_shows.length < 1  ||
          null !== filters.timezone_id
        )
      ) {
        filters.department = first(this.departments)?.id;
      } else if (
        !filters.city_id &&
        !filters.company_id &&
        !filters.commercial_experience &&
        !filters.country_id &&
        !filters.current_job_role_id &&
        !filters.desired_job_role_id &&
        !filters.keyword &&
        filters.next_availability.length < 1 &&
        !filters.preferred_location_id &&
        !filters.shortlist &&
        filters.skills.length < 1 &&
        filters.television_shows.length < 1 &&
        !filters.timezone_id &&
        null !== filters.department
      ) {
        filters.department = null;
        this.filters.department = null;
      }

      return filters;
    },

    fillData() {
      const shortlistItems = Object.entries(this.shortlists);

      if (shortlistItems.length === 0) {
        this.shortlists = [{
          id: 'create_another_list',
          title: this.$t('client.shortlist.create_another_list'),
        }];

        return this.shortlists;
      }

      this.shortlists = [];

      shortlistItems.forEach(([key, value]) => {
        this.shortlists.push({ id: value.id, title: value.title });
      });

      this.shortlists.push({
        id: 'create_another_list',
        title: this.$t('client.shortlist.create_another_list'),
      });
    },

    setKeyword() {
      this.filters.keyword = this.tempFilters.keyword;
    },

    addShortlist(payload) {
      this.shortlists.unshift({
        id: payload.id,
        title: payload.title,
      });
    },
  },
};
</script>
