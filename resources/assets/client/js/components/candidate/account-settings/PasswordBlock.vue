<template>
  <div id="account-settings-password-block">
    <b-form class="form-xs">
      <b-row class="mt-4">
        <b-col sm="6" class="mb-4 mb-sm-0">
          <label>{{ $t('client.account-settings.password') }}</label>
          <b-form-input
            v-model="candidateItem.password"
            type="password"
            :placeholder="$t('client.account-settings.password')"
            required
          />
          <small
            v-if="errors.password?.length > 0"
            class="text-danger"
          >
            {{ errors.password[0] }}
          </small>
        </b-col>
        <b-col sm="6">
          <b-form-group>
            <label>
              {{ $t('client.account-settings.password_confirmation') }}
            </label>
            <b-form-input
              v-model="candidateItem.password_confirmation"
              name="password_confirmation"
              type="password"
              :placeholder="$t('client.account-settings.password_confirmation')"
              required
            />
            <small
              v-if="errors.password_confirmation?.length > 0"
              class="text-danger"
            >
              {{ errors.password_confirmation[0] }}
            </small>
          </b-form-group>
        </b-col>
      </b-row>
      <div class="mt-4 text-right">
        <button
          type="submit"
          class="btn btn-primary text-blue"
          @click.prevent="submitForm"
        >
          {{ $t('client.account-settings.buttons.save') }}
        </button>
      </div>
    </b-form>
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
    submitForm() {
      this.$emit('submit', { candidate: this.candidateItem, blockKey: 'password' });
    },
  },
};
</script>
