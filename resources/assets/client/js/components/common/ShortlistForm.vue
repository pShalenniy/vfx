<template>
  <b-card class="mt-2">
    <b-form class="mt-2" @submit.prevent>
      <b-form-group>
        <b-form-input
          v-model="shortlist"
          type="text"
          :placeholder="$t('client.shortlist.form.shortlist')"
        />
      </b-form-group>

      <div class="d-flex flex-row my-2">
        <button
          type="button"
          class="btn btn-lg btn-secondary"
          @click="handleHiding"
        >
          {{ $t('client.shortlist.form.cancel') }}
        </button>
        <button
          type="submit"
          class="ml-2 btn btn-lg btn-primary text-blue"
          @click="submitForm"
        >
          {{ $t('client.shortlist.form.save') }}
        </button>
      </div>
    </b-form>
  </b-card>
</template>

<script>
export default {
  data() {
    return {
      shortlist: null,
    };
  },

  methods: {
    submitForm() {
      if (!this.shortlist) {
        this.$notify.error(this.$t('client.shortlist.action.create.empty_form'));

        return;
      }

      this.$emit('submit', this.shortlist);
    },

    handleHiding() {
      this.$emit('cancel');

      this.clearForm();
    },

    clearForm() {
      this.shortlist = null;
    },
  },
};
</script>
