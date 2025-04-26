<template>
  <div id="candidates-page">
    <candidate-create-modal
      v-if="hasPermissions('candidate.create')"
      @created="handleCreated"
    />

    <candidate-edit-modal
      v-if="hasPermissions('candidate.edit')"
      :editable-candidate="editModalProps.editableCandidate"
      @updated="handleUpdated"
    />

    <add-star-candidate-modal
      v-if="hasPermissions('candidate.mark-starred') && candidateId !== null"
      :candidate-id="candidateId"
    />

    <b-card no-body class="mb-0">
      <div class="m-2">
        <b-row>
          <b-col cols="6">
            <b-form-checkbox v-model="filters.starred_candidates">
              {{ $t('admin.candidate.filters.starred_candidates') }}
            </b-form-checkbox>
          </b-col>
          <b-col cols="6">
            <div class="d-flex align-items-center justify-content-end">
              <b-form-input
                v-model="filters.keyword"
                debounce="700"
                class="d-inline-block mr-1"
                :placeholder="$t('admin.candidate.filters.search')"
              />
              <b-button
                v-if="hasPermissions('candidate.create')"
                v-b-modal.modal-candidate-create
                variant="primary"
              >
                <span class="text-nowrap">{{ $t('admin.candidate.action.create.button') }}</span>
              </b-button>
            </div>
          </b-col>
        </b-row>
      </div>

      <b-table
        class="position-relative"
        :items="candidates"
        :responsive="true"
        :fields="tableColumns"
        primary-key="id"
        show-empty
        :empty-text="$t('admin.candidate.table.empty')"
        no-local-sorting
        :sort-by="sort.by"
        :sort-desc="sort.isDesc"
        @sort-changed="onSortChanged"
      >
        <template #cell(picture)="data">
          <img
            v-if="data.item.picture"
            :src="data.item.picture"
            width="50"
            height="50"
            alt=""
          />
          <img
            v-else
            :src="candidateDefaultPicturePath"
            width="50"
            height="50"
            alt=""
          />
        </template>
        <template #cell(company)="data">
          <p>
            {{ data.item.company?.name }}
          </p>
        </template>
        <template #cell(skills)="data">
          <ul class="px-50 mb-0">
            <li v-for="skill in data.item.skills" :key="skill.value">
              {{ skill.label }}
            </li>
          </ul>
        </template>
        <template #cell(current_job_roles)="data">
          <ul class="px-50 mb-0">
            <li v-for="currentJobRole in data.item.current_job_roles" :key="currentJobRole.id">
              {{ currentJobRole.name }}
            </li>
          </ul>
        </template>
        <template #cell(nationalities)="data">
          <ul class="px-50 mb-0">
            <li v-for="nationality in data.item.nationalities" :key="nationality.id">
              {{ nationality.name }}
            </li>
          </ul>
        </template>
        <template #cell(alternative_citizenship_residencies)="data">
          <ul class="px-50 mb-0">
            <li
              v-for="alternativeCitizenshipResidence in data.item.alternative_citizenship_residencies"
              :key="alternativeCitizenshipResidence.id"
            >
              {{ alternativeCitizenshipResidence.name }}
            </li>
          </ul>
        </template>
        <template #cell(next_availability)="data">
          <p v-if="data.item.next_availability" class="mt-1">
            {{ changeDateFormat(data.item.next_availability) }}
          </p>
        </template>
        <template #cell(social_media)="data">
          <a v-if="data.item.imdb_link" :href="data.item.imdb_link" target="_blank">
            <feather-icon icon="FilmIcon" />
          </a>
          <a v-if="data.item.linkedin_link" :href="data.item.linkedin_link" target="_blank">
            <feather-icon icon="LinkedinIcon" />
          </a>
          <a v-if="data.item.instagram_link" :href="data.item.instagram_link" target="_blank">
            <feather-icon icon="InstagramIcon" />
          </a>
          <a v-if="data.item.twitter_link" :href="data.item.twitter_link" target="_blank">
            <feather-icon icon="TwitterIcon" />
          </a>
        </template>
        <template #cell(actions)="data">
          <a
            v-if="hasPermissions('candidate.mark-starred')"
            href="#"
            @click.prevent="openAddStarCandidateModal(data.item)"
          >
            <feather-icon icon="StarIcon" class="text-warning" />
          </a>
          <a
            v-if="hasPermissions('candidate.edit')"
            href="#"
            @click.prevent="openCandidateEditModal(data.item)"
          >
            <feather-icon icon="EditIcon" size="16" />
          </a>
          <a
            v-if="hasPermissions('candidate.delete')"
            href="#"
            @click.prevent="deleteCandidate(data.item)"
          >
            <feather-icon icon="TrashIcon" size="16" class="text-danger" />
          </a>
        </template>
      </b-table>

      <div ref="footer" class="mx-2 mb-2">
        <b-row>
          <b-col
            cols="12"
            sm="6"
            class="d-flex align-items-center justify-content-center justify-content-sm-start"
          >
            <span class="text-muted">
              {{
                $t('common.showing_to_of_total_entries', {
                  from: meta.from,
                  to: meta.to,
                  total: meta.total,
                })
              }}
            </span>
          </b-col>
          <b-col
            cols="12"
            sm="6"
            class="d-flex align-items-center justify-content-center justify-content-sm-end"
          >
            <b-pagination
              v-model="meta.currentPage"
              :total-rows="meta.total"
              :per-page="meta.perPage"
              first-number
              last-number
              class="mb-0 mt-1 mt-sm-0"
              prev-class="prev-item"
              next-class="next-item"
            >
              <template #prev-text>
                <feather-icon
                  icon="ChevronLeftIcon"
                  size="18"
                />
              </template>
              <template #next-text>
                <feather-icon
                  icon="ChevronRightIcon"
                  size="18"
                />
              </template>
            </b-pagination>
          </b-col>
        </b-row>
      </div>
    </b-card>
  </div>
</template>

<script>
import { getDateInUKFormat } from '@common/js/helpers/date';
import budgetOfBiggestShowValues from '@common/js/constants/candidateBudgetOfBiggestShowConstants';
import salaryRateCurrencyValues from '@common/js/constants/candidateSalaryRateCurrencyConstants';
import hasPagination from '@common/js/mixins/hasPagination';
import hasSorting from '@admin/js/mixins/hasSorting';
import AddStarCandidateModal from '@admin/js/components/candidate/AddStarCandidateModal';
import CandidateCreateModal from '@admin/js/components/candidate/CandidateCreateModal';
import CandidateEditModal from '@admin/js/components/candidate/CandidateEditModal';

export default {
  components: {
    AddStarCandidateModal,
    CandidateCreateModal,
    CandidateEditModal,
  },

  mixins: [
    hasPagination,
    hasSorting,
  ],

  data() {
    return {
      candidateDefaultPicturePath: window[window.globalSettingsKey].candidateDefaultPicturePath,
      salaryRateCurrencyValues: Object.values(salaryRateCurrencyValues),
      budgetOfBiggestShowValues: Object.values(budgetOfBiggestShowValues),
      tableColumns: [
        {
          key: 'picture',
          label: this.$t('admin.candidate.table.columns.picture'),
        },
        {
          key: 'full_name',
          label: this.$t('admin.candidate.table.columns.full_name'),
          sortable: true,
        },
        {
          key: 'email',
          label: this.$t('admin.candidate.table.columns.email'),
          sortable: true,
        },
        {
          key: 'company',
          label: this.$t('admin.candidate.table.columns.company'),
          sortable: true,
        },
        {
          key: 'skills',
          label: this.$t('admin.candidate.table.columns.skills'),
          sortable: true,
        },
        {
          key: 'current_job_roles',
          label: this.$t('admin.candidate.table.columns.current_job_roles'),
          sortable: true,
        },
        {
          key: 'nationalities',
          label: this.$t('admin.candidate.table.columns.nationalities'),
          sortable: true,
        },
        {
          key: 'alternative_citizenship_residencies',
          label: this.$t('admin.candidate.table.columns.alternative_citizenship_residencies'),
          sortable: true,
        },
        {
          key: 'next_availability',
          label: this.$t('admin.candidate.table.columns.next_availability'),
          sortable: true,
          tdClass: ['text-nowrap'],
        },
        {
          key: 'created_at',
          label: this.$t('admin.candidate.table.columns.created_at'),
          sortable: true,
          tdClass: ['text-nowrap'],
        },
        {
          key: 'social_media',
          label: this.$t('admin.candidate.table.columns.social_media'),
          tdClass: ['text-nowrap'],
        },
        {
          key: 'actions',
          label: this.$t('admin.candidate.table.columns.actions'),
        },
      ],
      getDataMethod: 'getCandidates',
      candidates: [],
      candidateId: null,
      editModalProps: {
        editableCandidate: {
          id: null,
          tinsel_town_id: null,
          first_name: null,
          last_name: null,
          email: null,
          nationalities: [],
          city: {},
          region: {},
          country: {},
          timezone: {},
          company: {},
          television_shows: [],
          alternative_citizenship_residencies: [],
          budget_of_biggest_show: null,
          phone_number: null,
          portfolio_url: null,
          shortfilm_url: null,
          gross_annual_salary: null,
          week_rate: null,
          day_rate: null,
          commercial_experience: null,
          preferred_sectors: [],
          preferred_locations: [],
          travel_availability: null,
          salary_rate_currency: {},
          vfx_notes: null,
          skills: [],
          want_learn_skills: [],
          want_work_with_tools: [],
          desired_job_roles: [],
          current_job_roles: [],
          next_promotion_job_roles: [],
          preferred_work_environments: [],
          imdb_link: null,
          linkedin_link: null,
          instagram_link: null,
          twitter_link: null,
          next_availability: null,
          professional_interest: null,
          would_like_work_on: null,
          skill_circles: {},
          picture: null,
        },
      },
      filters: {
        keyword: null,
        starred_candidates: null,
      },
    };
  },

  created() {
    this.getCandidates();
  },

  methods: {
    async getCandidates() {
      const params = Object.assign(
        {},
        { page: this.meta.currentPage },
        this.filters,
        { 'sort[by]': this.sort.by, 'sort[direction]': this.sort.direction },
      );

      this.$overlay.show();

      try {
        const { data } = await axios.get(route('admin.candidate.list'), { params });

        this.candidates = this.getPrefilledCandidates(data.data);

        this.fillMeta(data);
      } catch (e) {
        console.error(e);
        this.$notify.errors(e);
      } finally {
        this.$overlay.hide();
      }
    },

    getPrefilledCandidates(candidates) {
      candidates.forEach((candidate) => {
        Object.keys(candidate).forEach((field) => {
          if (field === 'salary_rate_currency') {
            const salaryRateCurrencyData = Object.keys(salaryRateCurrencyValues).find((item) => {
              return candidate[field] === salaryRateCurrencyValues[item].value;
            });

            candidate[field] = {
              value: candidate[field],
              label: salaryRateCurrencyValues[salaryRateCurrencyData]?.label,
            };
          } else if (field === 'budget_of_biggest_show') {
            const budgetOfBiggestShowData = Object.keys(budgetOfBiggestShowValues).find((item) => {
              return candidate[field] === budgetOfBiggestShowValues[item].value;
            });

            candidate[field] = {
              value: candidate[field],
              label: budgetOfBiggestShowValues[budgetOfBiggestShowData]?.label,
            };
          } else if (field === 'skill_circles') {
            if (!candidate[field]) {
              this.editModalProps.editableCandidate[field] = {};
            }
          }
        });
      });

      return candidates;
    },

    async deleteCandidate(item) {
      const result = await this.$confirm.delete(
        item.tinsel_town_id
          ? this.$t('admin.common.change_action_warning')
          : this.$t('common.confirmation.delete.you_wont_revert'),
      );

      if (!result.isConfirmed) {
        return;
      }

      this.$overlay.show();

      try {
        await axios.delete(route('admin.candidate.destroy', item.id));

        this.$notify.success(this.$t('admin.candidate.action.delete.success'));

        await this.getCandidates();
      } catch (e) {
        console.error(e);
        this.$notify.errors(e);
      } finally {
        this.$overlay.hide();
      }
    },

    openCandidateEditModal(candidate) {
      this.editModalProps.editableCandidate = candidate;

      this.$bvModal.show('modal-candidate-edit');
    },

    openAddStarCandidateModal(candidate) {
      this.candidateId = candidate.id;

      this.$bvModal.show('modal-candidate-star-add');
    },

    changeDateFormat(date) {
      return getDateInUKFormat(date);
    },

    handleCreated() {
      this.getCandidates();
    },

    handleUpdated() {
      this.getCandidates();
    },
  },
};
</script>
