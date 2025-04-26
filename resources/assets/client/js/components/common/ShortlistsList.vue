<template>
  <div id="shortlists-list">
    <template v-if="activeShortlist">
      <a href="#" class="mb-2" @click.prevent="toggleActiveShortlist">
        <feather-icon icon="ArrowLeftIcon" size="16" />
      </a>
      <h2 class="shortlist-title mt-2 mb-4">
        {{ activeShortlist.title }}
      </h2>

      <shortlist-candidates-list
        :viewing-candidate="viewingCandidate"
        :candidate-items="activeShortlist.candidates"
        :shortlist-id="activeShortlist.id"
      />
    </template>
    <template v-else>
      <a
        href="#"
        class="btn btn-xs btn-rounded btn-dark mb-2 background-blue"
        @click.prevent="toggleShortlistCreating"
      >
        {{ $t('client.shortlist.buttons.add_shortlist') }}
      </a>
      <b-row class="mb-4">
        <b-col cols="12" lg="4">
          <shortlist-form
            v-show="isCreatingShortlist"
            ref="shortlistForm"
            @cancel="toggleShortlistCreating"
            @submit="store"
          />
        </b-col>
      </b-row>

      <div v-if="shortlists.length > 0" class="overflow-auto">
        <div class="row-table-responsive">
          <div
            v-for="shortlist in shortlists"
            :key="shortlist.id"
            class="shortlist-row"
          >
            <b-row class="shortlist-item-container align-items-center">
              <b-col cols="3">
                <p>{{ shortlist.title }}</p>
              </b-col>
              <b-col cols="4">
                <p>
                  {{ $t('client.user.account_settings.created') }}
                  {{ getDateInLocalFormat(shortlist.created_at) }}
                </p>
              </b-col>
              <b-col cols="3">
                <p>
                  {{ $tc('client.common.shortlist.candidates_in_shortlist', shortlist.candidates_count) }}
                </p>
                <div v-if="!isEmpty(shortlist.candidates) && viewingCandidate?.id">
                  <p v-show="isCandidateInShortlist(shortlist.candidates)" class="mt-2">
                    {{ $t('client.user.account_settings.exists_in_shortlist') }}
                  </p>
                </div>
              </b-col>
              <b-col cols="auto">
                <div class="d-flex align-items-center">
                  <div class="mr-3">
                    <a href="#" @click.prevent="showCandidatesList(shortlist)">
                      {{ $t('client.common.shortlist.edit') }}
                    </a>
                  </div>
                  <div>
                    <feather-icon
                      icon="TrashIcon"
                      size="20"
                      @click="deleteShortlist(shortlist.id)"
                    />
                  </div>
                </div>
              </b-col>
            </b-row>
            <hr class="my-4" />
          </div>
        </div>
      </div>
      <template v-else>
        <h3 class="h4 mb-0 mt-2 text-center">
          {{ $t('client.user.account_settings.shortlists.no_shortlists') }}
        </h3>
      </template>
    </template>
  </div>
</template>

<script>
import { getDateInLocalFormat } from '@common/js/helpers/date';
import hasShortlists from '@common/js/mixins/hasShortlists';
import ShortlistCandidatesList from '@client/js/components/common/ShortlistCandidatesList';
import ShortlistForm from '@client/js/components/common/ShortlistForm';

export default {
  components: {
    ShortlistForm,
    ShortlistCandidatesList,
  },

  mixins: [
    hasShortlists,
  ],

  props: {
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
      isCreatingShortlist: false,
      activeShortlist: null,
      candidates: [],
    };
  },

  computed: {
    isCandidateInShortlist() {
      return (candidates) => {
        return candidates.find((item) => {
          return this.viewingCandidate.id === item.id;
        });
      };
    },
  },

  methods: {
    getDateInLocalFormat,
    async store(payload) {
      this.$overlay.show();

      try {
        await axios.post(route('shortlist.store'), { title: payload });

        await this.getShortlistsWithoutOverlay();

        this.$notify.success(this.$t('client.shortlist.action.create.success'));

        this.$refs.shortlistForm.clearForm();

        this.toggleShortlistCreating();
      } catch (e) {
        console.error(e);
        this.$notify.errors(e);
      } finally {
        this.$overlay.hide();
      }
    },

    async deleteShortlist(shortlist) {
      const result = await this.$confirm.delete({
        title: this.$t('common.confirmation.delete_shortlist.title'),
        text: '',
        icon: '',
        confirmButtonText: this.$t('common.confirmation.delete_shortlist.confirm_button'),
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
        await axios.delete(route('shortlist.destroy', shortlist));

        this.$notify.success(this.$t('client.common.shortlist.action.delete_shortlist.success'));

        await this.getShortlists();
      } catch (e) {
        console.error(e);
        this.$notify.errors(e);
      } finally {
        this.$overlay.hide();
      }
    },

    toggleShortlistCreating() {
      this.isCreatingShortlist = !this.isCreatingShortlist;
    },

    toggleActiveShortlist() {
      this.activeShortlist = null;

      this.getShortlists();
    },

    showCandidatesList(shortlist) {
      this.activeShortlist = shortlist;
    },
  },
};
</script>
