<template>
  <div id="account-settings-contact-block">
    <template v-if="editableBlock !== 'contact'">
      <b-card
        v-if="
          null !== candidateItem.instagram_link ||
          null !== candidateItem.twitter_link ||
          null !== candidateItem.linkedin_link ||
          null !== candidateItem.linkedin_link ||
          null !== candidateItem.imdb_link ||
          candidateItem.phone_number
        "
        class="mt-2 text-medium"
      >
        <b-row v-if="null !== candidateItem.instagram_link" class="mb-4">
          <b-col cols="12">
            <p>
              <b>{{ $t('client.account-settings.instagram_link.label') }}</b>
              <a :href="candidateItem.instagram_link" target="_blank">
                {{ candidateItem.instagram_link }}
              </a>
            </p>
          </b-col>
        </b-row>
        <b-row v-if="null !== candidateItem.twitter_link" class="mb-4">
          <b-col cols="12">
            <p>
              <b>{{ $t('client.account-settings.twitter_link.label') }}</b>
              <a :href="candidateItem.twitter_link" target="_blank">
                {{ candidateItem.twitter_link }}
              </a>
            </p>
          </b-col>
        </b-row>
        <b-row v-if="null !== candidateItem.linkedin_link" class="mb-4">
          <b-col cols="12">
            <p>
              <b>{{ $t('client.account-settings.linkedin_link.label') }}</b>
              <a :href="candidateItem.linkedin_link" target="_blank">
                {{ candidateItem.linkedin_link }}
              </a>
            </p>
          </b-col>
        </b-row>
        <b-row v-if="null !== candidateItem.imdb_link" class="mb-4">
          <b-col cols="12">
            <p>
              <b>{{ $t('client.account-settings.imdb_link.label') }}</b>
              <a :href="candidateItem.imdb_link" target="_blank">
                {{ candidateItem.imdb_link }}
              </a>
            </p>
          </b-col>
        </b-row>
        <b-row v-if="candidateItem.phone_number">
          <b-col cols="12">
            <p>
              <b>{{ $t('client.account-settings.phone_number.label') }}</b>
              <a :href="`tel:${candidateItem.phone_number}`">
                {{ candidateItem.phone_number }}
              </a>
            </p>
          </b-col>
        </b-row>
      </b-card>
    </template>
    <template v-else>
      <b-card>
        <b-form class="form-xs">
          <b-row class="mt-4 align-items-center">
            <b-col sm="4" class="mb-2 mb-sm-0">
              <p>
                <b>
                  {{ $t('client.account-settings.instagram_link.label') }}
                </b>
              </p>
            </b-col>
            <b-col sm="8">
              <b-input
                v-model="candidateItem.instagram_link"
                type="url"
                :placeholder="$t('client.account-settings.instagram_link.placeholder')"
              />
              <small
                v-if="errors.instagram_link?.length > 0"
                class="text-danger"
              >
                {{ errors.instagram_link[0] }}
              </small>
            </b-col>
          </b-row>
          <b-row class="mt-4 align-items-center">
            <b-col sm="4" class="mb-2 mb-sm-0">
              <p>
                <b>
                  {{ $t('client.account-settings.twitter_link.label') }}
                </b>
              </p>
            </b-col>
            <b-col sm="8">
              <b-input
                v-model="candidateItem.twitter_link"
                type="url"
                :placeholder="$t('client.account-settings.twitter_link.placeholder')"
              />
              <small
                v-if="errors.twitter_link?.length > 0"
                class="text-danger"
              >
                {{ errors.twitter_link[0] }}
              </small>
            </b-col>
          </b-row>
          <b-row class="mt-4 align-items-center">
            <b-col sm="4" class="mb-2 mb-sm-0">
              <p>
                <b>
                  {{ $t('client.account-settings.linkedin_link.label') }}
                </b>
              </p>
            </b-col>
            <b-col sm="8">
              <b-input
                v-model="candidateItem.linkedin_link"
                type="url"
                :placeholder="$t('client.account-settings.linkedin_link.placeholder')"
              />
              <small
                v-if="errors.linkedin_link?.length > 0"
                class="text-danger"
              >
                {{ errors.linkedin_link[0] }}
              </small>
            </b-col>
          </b-row>
          <b-row class="mt-4 align-items-center">
            <b-col sm="4" class="mb-2 mb-sm-0">
              <p>
                <b>
                  {{ $t('client.account-settings.imdb_link.label') }}
                </b>
              </p>
            </b-col>
            <b-col sm="8">
              <b-input
                v-model="candidateItem.imdb_link"
                type="url"
                :placeholder="$t('client.account-settings.imdb_link.placeholder')"
              />
              <small
                v-if="errors.imdb_link?.length > 0"
                class="text-danger"
              >
                {{ errors.imdb_link[0] }}
              </small>
            </b-col>
          </b-row>
          <b-row class="mt-4 align-items-center">
            <b-col sm="4" class="mb-2 mb-sm-0">
              <p>
                <b>
                  {{ $t('client.account-settings.phone_number.label') }}
                </b>
              </p>
            </b-col>
            <b-col sm="8">
              <vue-tel-input
                :value="candidateItem.phone_number"
                @input="setPhoneNumber"
              />
              <small
                v-if="errors.phone_number?.length > 0"
                class="text-danger"
              >
                {{ errors.phone_number[0] }}
              </small>
            </b-col>
          </b-row>
          <div class="mt-4 text-right">
            <button
              class="btn btn-secondary"
              @click.prevent="cancel"
            >
              {{ $t('client.account-settings.buttons.cancel') }}
            </button>
            <button
              type="submit"
              class="btn btn-primary text-blue"
              @click.prevent="submitForm"
            >
              {{ $t('client.account-settings.buttons.save') }}
            </button>
          </div>
        </b-form>
      </b-card>
    </template>
  </div>
</template>

<script>
import cloneDeep from 'lodash/cloneDeep';

export default {
  props: {
    candidate: {
      type: Object,
      required: true,
    },
    editableBlock: {
      type: String,
      required: false,
      default() {
        return '';
      },
    },
    errors: {
      type: Array,
      required: false,
      default() {
        return [];
      },
    },
  },

  data() {
    return {
      candidateItem: cloneDeep(this.candidate),
    };
  },

  methods: {
    cancel() {
      this.$emit('cancel');
    },

    submitForm() {
      this.$emit('submit', { candidate: this.candidateItem, blockKey: this.editableBlock });
    },

    setPhoneNumber(phoneNumber, phoneNumberData) {
      this.candidateItem.phone_number = phoneNumberData.number;
    },
  },
};
</script>
