<template>
  <div id="candidate-page" class="section bg-secondary text-white">
    <div class="container" @click.self="toggleSelectedBlock">
      <shortlist-modal v-if="candidate" :viewing-candidate="candidate" />

      <message-modal v-if="candidate" :candidate="candidate" />

      <portfolio-modal v-if="candidate?.portfolio_url" :candidate="candidate" />

      <div class="row">
        <div class="candidate-actions ml-auto mb-4 btn-group col-md-auto">
          <button
            href="#"
            class="btn btn-lg btn-primary mb-2 mr-2 text-blue"
            @click.prevent="openMessageModal"
          >
            {{ $t('client.candidate.button.message') }}
          </button>
          <button
            class="btn btn-lg btn-primary mb-2 mr-2 text-blue"
            @click.prevent="openShortlistModal"
          >
            {{ $t('client.candidate.button.shortlist') }}
          </button>
          <button
            v-if="!viewAsList"
            class="btn btn-lg btn-primary mb-2 mr-2 text-blue"
            @click.prevent="toggleViewAsList"
          >
            {{ $t('client.candidate.button.view_as_list') }}
          </button>
          <button
            v-else
            class="btn btn-lg btn-primary text-blue"
            @click.prevent="toggleViewAsList"
          >
            {{ $t('client.candidate.button.view_as_block') }}
          </button>
        </div>
      </div>

      <div class="mb-5 row align-items-center profile-header" @click.self="toggleSelectedBlock">
        <div class="order-last order-md-first col-md-auto mt-md-0">
          <template v-if="!viewAsList">
            <p class="mb-1 text-big">
              <b>
                {{ candidate.full_name }}
              </b>
            </p>
            <p v-if="candidate.current_job_roles?.length > 0" class="mb-0 text-big">
              {{ getMultipleItemsInString(candidate.current_job_roles) }}
            </p>
          </template>
        </div>
      </div>

      <main-information-block v-if="viewAsList" :candidate="candidate" />

      <template v-else>
        <div v-if="!selectedBlock" class="profile-grid">
          <div class="row">
            <div class="col-6 col-md-4">
              <div class="profile-grid-card">
                <div class="card">
                  <picture-block :candidate="candidate" />
                </div>
              </div>
            </div>

            <div class="col-6 col-md-4">
              <div class="profile-grid-card">
                <div
                  class="card cursor-pointer"
                  @click.prevent="showSelectedBlock(candidate?.city?.longitude && candidate?.city?.latitude ? 'map' : null)"
                >
                  <map-block :candidate="candidate" />
                </div>
              </div>
            </div>
            <div class="col-6 col-md-4">
              <next-availability-block
                v-if="candidate?.next_availability"
                :candidate="candidate"
              />
            </div>
            <div class="col-6 col-md-4">
              <div class="profile-grid-card background-blue">
                <div class="card cursor-pointer" @click.prevent="showSelectedBlock('experience')">
                  <p class="h1 mb-0">
                    <b>
                      {{ $t('client.candidate.content_section.blocks.experience') }}
                    </b>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-6 col-md-4">
              <div class="profile-grid-card background-blue">
                <div class="card cursor-pointer" @click.prevent="showSelectedBlock('interests')">
                  <p class="h1 mb-0">
                    <b>
                      {{ $t('client.candidate.content_section.blocks.interests') }}
                    </b>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-6 col-md-4">
              <div class="profile-grid-card background-blue">
                <div class="card cursor-pointer" @click.prevent="showSelectedBlock('personal')">
                  <p class="h1 mb-0">
                    <b>
                      {{ $t('client.candidate.content_section.blocks.personal') }}
                    </b>
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div v-if="candidate.portfolio_url || candidate.shortfilm_url" class="row mt-4">
            <div v-if="candidate.portfolio_url" class="col-sm-6">
              <div
                class="profile-grid-card cursor-pointer"
                @click.prevent="showSelectedBlock('portfolio')"
              >
                <p class="h2 top-center mb-0">
                  {{ $t('client.candidate.content_section.blocks.portfolio') }}
                </p>

                <portfolio-block
                  class="portfolio-block"
                  :candidate="candidate"
                  field="portfolio_url"
                />
              </div>
            </div>
            <div v-if="candidate.shortfilm_url" class="col-sm-6">
              <div class="profile-grid-card">
                <div class="card cursor-pointer" @click.prevent="showSelectedBlock('short-film')">
                  <portfolio-block
                    class="portfolio-block"
                    :candidate="candidate"
                    field="shortfilm_url"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>

        <div v-show="selectedBlock" ref="expandedBlock" class="candidate-expanded-block">
          <div class="candidate-expanded-block-header mb-1">
            <feather-icon
              class="cursor-pointer"
              icon="XIcon"
              size="32"
              @click.prevent="toggleSelectedBlock"
            />
          </div>

          <div v-show="selectedBlock === 'map'" class="expanded-map">
            <map-block
              :candidate="candidate"
              class="card map-card overflow-hidden mx-auto text-body"
            />
          </div>

          <div v-show="selectedBlock === 'experience'" class="expanded-experience">
            <experience-block :candidate="candidate" class="card card-body text-body" />

            <div class="col-sm-2 offset-10">
              <p
                class="next-block-button cursor-pointer"
                @click.prevent="showSelectedBlock('interests')"
              >
                {{ $t('client.candidate.content_section.blocks.buttons.see_interests') }}
                <feather-icon icon="ChevronRightIcon" />
              </p>
            </div>
          </div>

          <div v-show="selectedBlock === 'interests'" class="expanded-interests">
            <interests-block :candidate="candidate" class="card card-body text-body" />

            <div class="col-sm-2 offset-10 mt-10">
              <p
                class="next-block-button cursor-pointer"
                @click.prevent="showSelectedBlock('personal')"
              >
                {{ $t('client.candidate.content_section.blocks.buttons.see_personal') }}
                <feather-icon icon="ChevronRightIcon" />
              </p>
            </div>
          </div>

          <div v-show="selectedBlock === 'personal'" class="expanded-personal">
            <personal-block
              :candidate="candidate"
              class="card card-body text-body"
              @close="showSelectedBlock()"
            />
          </div>

          <div v-show="selectedBlock === 'portfolio'" class="expanded-portfolio">
            <portfolio-block
              :candidate="candidate"
              field="portfolio_url"
              class="d-flex justify-content-center"
            />
          </div>

          <div v-show="selectedBlock === 'short-film'" class="expanded-short-film">
            <portfolio-block
              :candidate="candidate"
              field="shortfilm_url"
              class="d-flex justify-content-center"
            />
          </div>
        </div>
      </template>
    </div>

    <div class="section">
      <div class="container">
        <alternative-talents-block :candidate="candidate" />
      </div>
    </div>
  </div>
</template>

<script>
import hasMultipleItems from '@client/js/mixins/hasMultipleItems';
import AlternativeTalentsBlock from '@client/js/components/candidate/AlternativeTalentsBlock';
import ExperienceBlock from '@client/js/components/candidate/ExperienceBlock';
import InterestsBlock from '@client/js/components/candidate/InterestsBlock';
import MainInformationBlock from '@client/js/components/candidate/MainInformationBlock';
import MapBlock from '@client/js/components/candidate/MapBlock';
import MessageModal from '@client/js/components/candidate/MessageModal';
import NextAvailabilityBlock from '@client/js/components/candidate/NextAvailabilityBlock';
import PersonalBlock from '@client/js/components/candidate/PersonalBlock';
import PictureBlock from '@client/js/components/candidate/PictureBlock';
import PortfolioBlock from '@client/js/components/candidate/PortfolioBlock';
import ShortlistModal from '@client/js/components/common/ShortlistModal';
import PortfolioModal from '@client/js/components/candidate/PortfolioModal';

export default {
  components: {
    PortfolioModal,
    AlternativeTalentsBlock,
    ExperienceBlock,
    InterestsBlock,
    MainInformationBlock,
    MapBlock,
    MessageModal,
    NextAvailabilityBlock,
    PersonalBlock,
    PictureBlock,
    PortfolioBlock,
    ShortlistModal,
  },

  mixins: [
    hasMultipleItems,
  ],

  data() {
    return {
      candidate: Object.freeze(window[window.globalSettingsKey].candidate),
      selectedBlock: null,
      viewAsList: false,
    };
  },

  methods: {
    openMessageModal() {
      this.$bvModal.show('modal-message');
    },

    openShortlistModal() {
      this.$bvModal.show('modal-shortlist');
    },

    toggleViewAsList() {
      this.viewAsList = !this.viewAsList;

      this.selectedBlock = null;
    },

    showSelectedBlock(block = null) {
      this.selectedBlock = block;

      this.$nextTick(() => {
        window.dispatchEvent(new Event('resize'));

        window.scrollTo({
          top: this.$refs.expandedBlock.offsetTop || 0,
          behavior: 'smooth',
        });
      });
    },

    toggleSelectedBlock() {
      if (null !== this.selectedBlock) {
        this.selectedBlock = null;
      }
    },
  },
};
</script>
