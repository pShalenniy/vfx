<template>
  <b-card
    no-body
    class="mb-0 our-partners"
  >
    <our-partner-create-modal
      v-if="hasPermissions('cms.block.our-partners.create')"
      @created="getOurPartners"
    />

    <our-partner-edit-modal
      v-if="hasPermissions('cms.block.our-partners.edit')"
      :editable-our-partner="editModalProps.editableOurPartner"
      @updated="getOurPartners"
    />
    <div class="m-2">
      <b-row>
        <b-col
          cols="12"
          md="6"
          offset-md="6"
        >
          <div class="d-flex align-items-center justify-content-end">
            <b-button
              v-if="hasPermissions('cms.block.our-partners.create')"
              v-b-modal.modal-our-partner-create
              variant="primary"
            >
              <span class="text-nowrap">{{ $t('admin.our-partner.action.create.button') }}</span>
            </b-button>
          </div>
        </b-col>
      </b-row>
    </div>

    <b-table
      class="position-relative"
      :items="ourPartners"
      :responsive="true"
      :fields="tableColumns"
      primary-key="id"
      show-empty
      :empty-text="$t('admin.our-partner.table.empty')"
    >
      <template #cell(logo)="data">
        <img :src="data.item.logo" alt="" />
      </template>
      <template #cell(actions)="data">
        <a
          v-if="hasPermissions('cms.block.our-partners.edit')"
          href="#"
          @click.prevent="openOurPartnerEditModal(data.item)"
        >
          <feather-icon icon="EditIcon" size="16" />
        </a>
        <a
          v-if="hasPermissions('cms.block.our-partners.delete')"
          href="#"
          @click.prevent="deleteOurPartner(data.item.id)"
        >
          <feather-icon icon="TrashIcon" size="16" class="text-danger" />
        </a>
      </template>
    </b-table>
  </b-card>
</template>

<script>
import OurPartnerCreateModal from '@admin/js/components/our-partner/OurPartnerCreateModal';
import OurPartnerEditModal from '@admin/js/components/our-partner/OurPartnerEditModal';

export default {
  components: {
    OurPartnerCreateModal,
    OurPartnerEditModal,
  },

  data() {
    return {
      tableColumns: [
        { key: 'id', label: this.$t('admin.our-partner.table.columns.id') },
        { key: 'logo', label: this.$t('admin.our-partner.table.columns.logo') },
        { key: 'created_at', label: this.$t('admin.our-partner.table.columns.created_at') },
        { key: 'actions', label: this.$t('admin.our-partner.table.columns.actions') },
      ],
      ourPartners: [],
      editModalProps: {
        editableOurPartner: {},
      },
    };
  },

  created() {
    this.getOurPartners();
  },

  methods: {
    async getOurPartners() {
      this.$overlay.show();

      try {
        const { data } = await axios.get(route('admin.our-partner.list'));

        this.ourPartners = data.data;
      } catch (e) {
        this.$notify.errors(e);
      } finally {
        this.$overlay.hide();
      }
    },

    async deleteOurPartner(id) {
      const result = await this.$confirm.delete();

      if (!result.isConfirmed) {
        return;
      }

      this.$overlay.show();

      try {
        await axios.delete(route('admin.our-partner.destroy', id));

        await this.getOurPartners();

        this.$notify.success(this.$t('admin.our-partner.action.delete.success'));
      } catch (e) {
        console.error(e);
        this.$notify.errors(e);
      } finally {
        this.$overlay.hide();
      }
    },

    openOurPartnerEditModal(ourPartner) {
      this.editModalProps.editableOurPartner = ourPartner;

      this.$bvModal.show('modal-our-partner-edit');
    },
  },
};
</script>
