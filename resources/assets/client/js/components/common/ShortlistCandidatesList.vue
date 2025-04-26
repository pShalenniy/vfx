<template>
  <div id="shortlist-candidates-list">
    <template v-if="!isEmpty(candidates)">
      <div v-for="candidateItem in candidates" :key="candidateItem.id">
        <b-row class="candidate-item-container">
          <b-col cols="2">
            <div class="mt-2">
              <img
                v-if="candidateItem.picture"
                :src="candidateItem.picture"
                alt=""
                height="30"
                width="30"
              />
              <img
                v-else
                :src="candidateDefaultPicturePath"
                alt=""
                height="30"
                width="30"
              />
            </div>
          </b-col>
          <b-col cols="2">
            <p class="mt-2">
              <a
                :href="route('candidate.show', candidateItem.slug)"
              >
                {{ candidateItem.full_name }}
              </a>
            </p>
          </b-col>
          <b-col cols="2">
            <p class="mt-2">
              {{ candidateItem.company?.name }}
            </p>
          </b-col>
          <b-col cols="3">
            <div v-if="candidateItem.current_job_roles?.length > 0" class="mt-2">
              <ul class="mb-0">
                <li
                  v-for="currentJobRole in candidateItem.current_job_roles"
                  :key="currentJobRole.id"
                  class="py-1"
                >
                  {{ `- ${currentJobRole.name}` }}
                </li>
              </ul>
            </div>
          </b-col>
          <b-col cols="2">
            <p class="mt-2">
              {{ getDateInLocalFormat(candidateItem.next_availability) }}
            </p>
          </b-col>
          <b-col cols="1">
            <div>
              <feather-icon
                icon="TrashIcon"
                size="20"
                @click="deleteCandidate(candidateItem.id)"
              />
            </div>
          </b-col>
        </b-row>
        <hr class="my-3" />
      </div>
    </template>
    <template v-else>
      <h3 class="h4 mb-0 mt-2 text-center">
        {{ $t('client.common.shortlist.no_candidates') }}
      </h3>
    </template>
    <div v-if="!isEmpty(viewingCandidate)" class="mt-4">
      <b-row>
        <b-col cols="6">
          <a
            v-if="!isCandidateInShortlist"
            class="btn btn-xs btn-rounded btn-dark background-blue"
            href="#"
            @click.prevent="syncCandidate"
          >
            {{ $t('client.shortlist.buttons.add_candidate') }}
          </a>
        </b-col>
        <b-col cols="3" offset-md="3">
          <a
            :href="route('candidate.page.list')"
            class="btn btn-xs btn-rounded btn-dark background-blue"
          >
            {{ $t('client.shortlist.buttons.back_to_search') }}
          </a>
        </b-col>
      </b-row>
    </div>
  </div>
</template>

<script>
import cloneDeep from 'lodash/cloneDeep';
import { getDateInLocalFormat } from '@common/js/helpers/date';
import hasShortlists from '@common/js/mixins/hasShortlists';

export default {
  mixins: [
    hasShortlists,
  ],

  props: {
    candidateItems: {
      type: Array,
      required: true,
    },
    shortlistId: {
      type: Number,
      required: false,
      default() {
        return 0;
      },
    },
    viewingCandidate: {
      type: Object,
      required: false,
      default() {
        return {};
      },
    },
  },

  data() {
    return {
      candidates: cloneDeep(this.candidateItems),
      candidateDefaultPicturePath: window[window.globalSettingsKey].candidateDefaultPicturePath,
    };
  },

  computed: {
    isCandidateInShortlist() {
      return this.candidateItems.find((item) => {
        return this.viewingCandidate.id === item.id;
      });
    },
  },

  watch: {
    candidateItems(newValue, oldValue) {
      if (newValue !== oldValue) {
        this.candidates = cloneDeep(newValue);
      }
    },
  },

  methods: {
    getDateInLocalFormat,
    async deleteCandidate(candidate) {
      const result = await this.$confirm.delete({
        title: this.$t('common.confirmation.delete_candidate_from_shortlist.title'),
        text: '',
        icon: '',
        confirmButtonText: this.$t('common.confirmation.delete_candidate_from_shortlist.confirm_button'),
        cancelButtonText: this.$t('common.confirmation.cancel'),
        confirmButtonColor: '#DBAC35',
        cancelButtonColor: '#062E52',
        customClass: null,
      });

      if (!result.isConfirmed) {
        return;
      }

      this.$overlay.show();

      try {
        await axios.post(route(
          'shortlist.candidate.detach',
          { shortlist: this.shortlistId, candidate },
        ));

        this.removeCandidate(candidate);

        this.$notify.success(this.$t('client.common.shortlist.action.delete.success'));
      } catch (e) {
        console.error(e);
        this.$notify.errors(e);
      } finally {
        this.$overlay.hide();
      }
    },

    async syncCandidate() {
      const candidates = [];

      this.candidates.forEach((candidate) => {
        candidates.push(candidate.id);
      });

      if (!candidates.includes(this.viewingCandidate.id)) {
        candidates.push(this.viewingCandidate.id);

        this.$overlay.show();

        try {
          await axios.patch(route('shortlist.candidate.sync', this.shortlistId), { candidates });

          this.candidates.push(this.viewingCandidate);

          this.$notify.success(this.$t('client.shortlist.action.sync_candidate.success'));
        } catch (e) {
          console.error(e);
          this.$notify.errors(e);
        } finally {
          this.$overlay.hide();
        }
      }
    },

    removeCandidate(candidate) {
      const index = this.candidates.findIndex((candidateItem) => candidateItem.id === candidate);

      if (this.candidates.length > 0 && index >= 0) {
        this.candidates.splice(index, 1);
      } else {
        this.candidates = [];
      }
    },
  },
};
</script>
