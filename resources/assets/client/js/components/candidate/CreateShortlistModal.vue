<template>
  <b-modal
    id="modal-shortlist-create"
    :title="$t('client.shortlist.modal.create')"
    no-close-on-backdrop
    size="lg"
    @ok="store"
    @cancel="clearForm"
  >
    <b-form class="mt-2">
      <b-form-group>
        <b-form-input
          v-model="shortlist"
          type="text"
          :placeholder="$t('client.shortlist.form.shortlist')"
          required
        />
      </b-form-group>
    </b-form>
  </b-modal>
</template>

<script>
export default {
  data() {
    return {
      shortlist: null,
    };
  },

  methods: {
    async store() {
      this.$overlay.show();

      try {
        const { data } = await axios.post(route('shortlist.store'), { title: this.shortlist });

        this.$notify.success(this.$t('client.shortlist.action.create.success'));

        this.$bvModal.hide('modal-shortlist-create');

        if (data.data) {
          this.$emit('created', data.data);
        }

        this.clearForm();
      } catch (e) {
        console.error(e);
        this.$notify.errors(e);
      } finally {
        this.$overlay.hide();
      }
    },

    clearForm() {
      this.shortlist = null;
    },
  },
};
</script>
