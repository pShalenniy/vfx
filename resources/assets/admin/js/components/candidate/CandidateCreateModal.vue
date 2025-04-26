<template>
  <b-modal
    id="modal-candidate-create"
    no-close-on-backdrop
    size="xl"
    @ok="ok"
  >
    <candidate-form
      ref="form"
      :candidate="candidate"
      :errors="errors"
      @submit="store"
    />
  </b-modal>
</template>

<script>
import jsonToFormData from 'json-form-data';
import CandidateForm from '@admin/js/components/candidate/CandidateForm';

export default {
  components: {
    CandidateForm,
  },

  data() {
    return {
      candidate: {
        first_name: null,
        last_name: null,
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
        travel_availability: false,
        salary_rate_currency: {},
        vfx_notes: null,
        picture: null,
        skills: [],
        want_learn_skills: [],
        want_work_with_tools: [],
        desired_job_roles: [],
        current_job_roles: [],
        next_promotion_job_roles: [],
        preferred_work_environments: [],
        would_like_work_on: null,
        email: null,
        imdb_link: null,
        linkedin_link: null,
        instagram_link: null,
        twitter_link: null,
        next_availability: null,
        professional_interest: null,
        skill_circles: {},
      },
      errors: [],
    };
  },

  methods: {
    ok($event) {
      $event.preventDefault();

      this.$refs.form.submitForm();
    },

    async store(candidate) {
      this.$overlay.show();

      this.errors = [];

      const data = jsonToFormData(Object.assign({}, candidate));

      try {
        await axios.post(route('admin.candidate.store'),
          data,
          {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          },
        );

        this.$notify.success(this.$t('admin.candidate.action.create.success'));

        this.$bvModal.hide('modal-candidate-create');

        this.$emit('created');
      } catch (error) {
        if (error.response.data.errors) {
          this.errors = error.response.data.errors;
          console.error(error.response.data.errors);
        } else if (error.response.data.message) {
          this.$notify.errors(error.response.data.message);
          console.error(error.response.data.message);
        }
      } finally {
        this.$overlay.hide();
      }
    },
  },
};
</script>
