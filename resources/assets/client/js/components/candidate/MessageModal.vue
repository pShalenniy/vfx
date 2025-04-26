<template>
  <b-modal
    id="modal-message"
    size="lg"
    no-close-on-backdrop
    hide-footer
  >
    <template #modal-title>
      <h2 class="shortlist-modal-title">
        {{ $t('client.message.modal.title') }}
      </h2>
    </template>
    <b-form>
      <b-form-group>
        <label>{{ $t('client.message.modal.form.from') }}</label>
        <b-input
          :value="userFullName"
          type="text"
          :placeholder="$t('client.message.modal.form.from')"
          disabled
          required
        />
      </b-form-group>
      <b-form-group>
        <label>{{ $t('client.message.modal.form.to') }}</label>
        <b-input
          :value="candidate.full_name"
          type="text"
          :placeholder="$t('client.message.modal.form.to')"
          disabled
          required
        />
      </b-form-group>
      <b-form-group>
        <label>{{ $t('client.message.modal.form.subject') }}</label>
        <b-input
          v-model="messageData.subject"
          type="text"
          :placeholder="$t('client.message.modal.form.subject')"
          required
        />
      </b-form-group>
      <b-form-group>
        <label>{{ $t('client.message.modal.form.message') }}</label>
        <b-form-textarea
          v-model="messageData.message"
          type="text"
          :placeholder="$t('client.message.modal.form.message')"
          rows="4"
          required
        />
      </b-form-group>
    </b-form>
    <div class="mt-3">
      <b-button @click="sendMessage">
        {{ $t('client.message.modal.buttons.send') }}
      </b-button>
      <a href="#" class="ml-2" style="text-decoration: underline" @click.prevent="closeModal">
        {{ $t('client.message.modal.buttons.return_to_candidate') }}
      </a>
    </div>
  </b-modal>
</template>

<script>
export default {
  props: {
    candidate: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      user: Object.freeze(window[window.globalSettingsKey].user),
      messageData: {
        subject: null,
        message: null,
      },
    };
  },

  computed: {
    userFullName() {
      return this.user.first_name + ' ' + this.user.last_name;
    },
  },

  methods: {
    async sendMessage() {
      this.$overlay.show();

      try {
        await axios.post(route('candidate.send-message', this.candidate.id), this.messageData);

        this.$notify.success(this.$t('client.message.modal.action.send.success'));

        this.closeModal();
      } catch (e) {
        console.error(e);
        this.$notify.errors(e);
      } finally {
        this.$overlay.hide();
      }
    },

    closeModal() {
      this.$bvModal.hide('modal-message');

      this.clearForm();
    },

    clearForm() {
      Object.keys(this.messageData).forEach((key) => {
        this.messageData[key] = null;
      });
    },
  },
};
</script>
