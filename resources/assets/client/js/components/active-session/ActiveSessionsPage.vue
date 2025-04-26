<template>
  <section id="active-sessions-page" class="section active-sessions-page bg-primary">
    <div class="container">
      <b-row>
        <b-col md="8" class="mx-auto">
          <b-card>
            <h2 class="h2 mb-4">
              {{ $t('client.active-session.title') }}
            </h2>
            <div v-html="text" />
            <div class="overflow-auto row">
              <div class="row-table-responsive col-12">
                <b-row class="align-items-center mt-4">
                  <b-col cols="2">
                    <p>{{ $t('client.active-session.os') }}</p>
                  </b-col>
                  <b-col cols="2">
                    <p>{{ $t('client.active-session.browser') }}</p>
                  </b-col>
                  <b-col cols="2">
                    <p>{{ $t('client.active-session.ip') }}</p>
                  </b-col>
                  <b-col col>
                    <p>{{ $t('client.active-session.last_activated_at') }}</p>
                  </b-col>
                  <b-col cols="auto">
                    {{ $t('client.active-session.action.title') }}
                  </b-col>
                </b-row>
                <hr class="mt-2" />
                <div v-for="(activeSession, index) in activeSessions" :key="activeSession.id">
                  <b-row class="align-items-center mt-2">
                    <b-col cols="2">
                      <p>{{ activeSession.os }}</p>
                    </b-col>
                    <b-col cols="2">
                      <p>{{ activeSession.browser }}</p>
                    </b-col>
                    <b-col cols="2">
                      <p>{{ activeSession.ip }}</p>
                    </b-col>
                    <b-col col>
                      <p>
                        {{ getDateInLocalFormat(activeSession.last_activated_at, true) }}
                      </p>
                    </b-col>
                    <b-col cols="auto">
                      <a
                        href="#"
                        class="btn btn-xs btn-rounded btn-dark"
                        @click.prevent="deleteActiveSession(activeSession, index)"
                      >
                        {{ $t('client.active-session.button.delete') }}
                      </a>
                    </b-col>
                  </b-row>
                  <hr class="mt-2" />
                </div>

                <b-row v-show="activeSessions.length < allowCount">
                  <b-col cols="12">
                    <a
                      :href="route('candidate.page.list')"
                      class="btn btn-xs btn-rounded btn-dark float-right"
                    >
                      {{ $t('client.active-session.continue') }}
                    </a>
                  </b-col>
                </b-row>
              </div>
            </div>
          </b-card>
        </b-col>
      </b-row>
    </div>
  </section>
</template>

<script>
import { getDateInLocalFormat } from '@common/js/helpers/date';
import contentData from '@common/js/constants/contentData';

export default {
  data() {
    return {
      activeSessions: window[window.globalSettingsKey].activeSessions,
      allowCount: Object.freeze(window[window.globalSettingsKey].allowCount),
      text: Object.freeze(
        window[window.globalSettingsKey].page[contentData.KEY_PAGE_ACTIVE_SESSIONS_TEXT],
      ),
    };
  },

  methods: {
    getDateInLocalFormat,
    async deleteActiveSession(activeSession, index) {
      const result = await this.$confirm.delete();

      if (!result.isConfirmed) {
        return;
      }

      this.$overlay.show();

      try {
        await axios.delete(route('active-session.destroy', activeSession.id));

        this.activeSessions.splice(index, 1);

        if (this.isEmpty(this.activeSessions)) {
          window.location.href = route('candidate.page.list');
        }

        this.$notify.success(this.$t('client.active-session.action.delete.success'));
      } catch (e) {
        console.error(e);
        this.$notify.errors(e);
      } finally {
        this.$overlay.hide();
      }
    },
  },
};
</script>
