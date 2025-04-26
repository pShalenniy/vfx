<template>
  <b-modal
    id="modal-candidate-star-add"
    no-close-on-backdrop
    @ok="addStarCandidate"
  >
    <b-row>
      <b-col cols="6">
        <label>{{ $t('admin.candidate.modal.start_period') }}</label>
        <b-form-datepicker
          v-model="start_period"
          :min="calendarOptions.minDate"
          :date-format-options="calendarOptions.dateFormatOptions"
          :start-weekday="calendarOptions.startWeekday"
          :locale="calendarOptions.locale"
          :placeholder="$t('admin.candidate.modal.start_period')"
        />
      </b-col>
      <b-col cols="6">
        <label>{{ $t('admin.candidate.modal.end_period') }}</label>
        <b-form-datepicker
          v-model="end_period"
          :min="calendarOptions.minDate"
          :date-format-options="calendarOptions.dateFormatOptions"
          :start-weekday="calendarOptions.startWeekday"
          :locale="calendarOptions.locale"
          :placeholder="$t('admin.candidate.modal.end_period') "
        />
      </b-col>
    </b-row>
  </b-modal>
</template>

<script>
export default {
  props: {
    candidateId: {
      type: Number,
      required: true,
    },
  },

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
      start_period: null,
      end_period: null,
    };
  },

  methods: {
    async addStarCandidate($event) {
      $event.preventDefault();

      this.$overlay.show();

      try {
        await axios.post(
          route('admin.candidate.mark-starred', this.candidateId),
          { start_period: this.start_period, end_period: this.end_period },
        );

        this.$notify.success(this.$t('admin.candidate.action.add_star_candidate'));

        this.$bvModal.hide('modal-candidate-star-add');
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
