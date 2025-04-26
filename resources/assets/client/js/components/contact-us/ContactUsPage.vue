<template>
  <section class="section">
    <div class="container">
      <div id="contact-us-page">
        <h1 v-if="isSubmitted" class="h1 text-center">
          {{ $t('client.contact-us.form.notification.success') }}
        </h1>
        <b-form v-else>
          <h1 class="h1 section-title text-light-blue">
            {{ $t('client.contact-us.form.title') }}
          </h1>
          <p class="mt-3 text-big">
            {{ disclaimerEmailText }}
            <b>{{ disclaimerEmail }}</b>
            {{ disclaimer }}
          </p>
          <b-form-group class="mt-4" :label="$t('client.contact-us.form.name')">
            <b-row>
              <b-col md="6">
                <b-form-input
                  v-model="data.first_name"
                  type="text"
                  :placeholder="$t('client.contact-us.form.first_name')"
                  required
                />
                <small
                  v-if="errors.first_name?.length > 0"
                  class="text-danger"
                >
                  {{ errors.first_name[0] }}
                </small>
              </b-col>
              <b-col md="6">
                <b-form-input
                  v-model="data.last_name"
                  type="text"
                  :placeholder="$t('client.contact-us.form.last_name')"
                  required
                />
                <small
                  v-if="errors.last_name?.length > 0"
                  class="text-danger"
                >
                  {{ errors.last_name[0] }}
                </small>
              </b-col>
            </b-row>
          </b-form-group>
          <b-form-group>
            <b-row>
              <b-col md="6">
                <b-form-group :label="$t('client.contact-us.form.email')">
                  <b-form-input
                    v-model="data.email"
                    type="email"
                    :placeholder="$t('client.contact-us.form.email')"
                    required
                  />
                  <small
                    v-if="errors.email?.length > 0"
                    class="text-danger"
                  >
                    {{ errors.email[0] }}
                  </small>
                </b-form-group>
              </b-col>
              <b-col md="6">
                <b-form-group :label="$t('client.contact-us.form.telephone_number')">
                  <b-form-input
                    v-model="data.telephone_number"
                    type="email"
                    :placeholder="$t('client.contact-us.form.telephone_number')"
                    required
                  />
                  <small
                    v-if="errors.telephone_number?.length > 0"
                    class="text-danger"
                  >
                    {{ errors.telephone_number[0] }}
                  </small>
                </b-form-group>
              </b-col>
            </b-row>
          </b-form-group>
          <b-form-group :label="$t('client.contact-us.form.enquiry')">
            <b-form-textarea
              v-model="data.enquiry"
              type="text"
              rows="3"
              required
            />
            <small
              v-if="errors.enquiry?.length > 0"
              class="text-danger"
            >
              {{ errors.enquiry[0] }}
            </small>
          </b-form-group>
          <button
            type="submit"
            class="btn btn-primary text-blue"
            @click.prevent="send"
          >
            {{ $t('client.contact-us.form.submit_button') }}
          </button>
        </b-form>
      </div>
    </div>
  </section>
</template>

<script>
import isEmpty from 'lodash/isEmpty';
import contentData from '@common/js/constants/contentData';

export default {
  data() {
    return {
      data: {
        first_name: null,
        last_name: null,
        email: null,
        telephone_number: null,
        enquiry: null,
      },
      page: window[window.globalSettingsKey].page,
      disclaimer: Object.freeze(
        window[window.globalSettingsKey].page[contentData.KEY_PAGE_CONTACT_US_DISCLAIMER],
      ),
      disclaimerEmail: Object.freeze(
        window[window.globalSettingsKey].page[contentData.KEY_PAGE_CONTACT_US_DISCLAIMER_EMAIL],
      ),
      disclaimerEmailText: Object.freeze(
        window[window.globalSettingsKey].page[contentData.KEY_PAGE_CONTACT_US_DISCLAIMER_EMAIL_TEXT],
      ),
      isSubmitted: false,
      errors: {},
    };
  },

  methods: {
    async send() {
      this.errors = {};

      for (const key of Object.keys(this.data)) {
        if (!this.data[key]) {
          this.errors[key] = [
            this.$t('validation.required', { attribute: this.$t(`client.contact-us.form.${key}`) }),
          ];
        }
      }

      if (!isEmpty(this.errors)) {
        return;
      }

      this.$overlay.show();

      try {
        await axios.post(route('contact-us.post'), this.data);

        this.isSubmitted = true;

        this.$notify.success(this.$t('client.contact-us.form.notification.success'));
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
