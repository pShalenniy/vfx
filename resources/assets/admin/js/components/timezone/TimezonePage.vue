<template>
  <div id="timezones-page">
    <timezone-create-modal v-if="hasPermissions('timezone.create')" />

    <timezone-edit-modal
      v-if="hasPermissions('timezone.edit')"
      :editable-timezone="editModalProps.editableTimezone"
    />

    <b-card no-body class="mb-0">
      <div class="m-2">
        <b-row>
          <b-col
            cols="12"
            md="6"
            offset-md="6"
          >
            <div class="d-flex align-items-center justify-content-end">
              <b-button
                v-if="hasPermissions('timezone.create')"
                v-b-modal.modal-timezone-create
                variant="primary"
              >
                <span class="text-nowrap">{{ $t('admin.timezone.action.create.button') }}</span>
              </b-button>
            </div>
          </b-col>
        </b-row>
      </div>

      <b-table
        class="position-relative"
        :items="timezones"
        :responsive="true"
        :fields="tableColumns"
        :per-page="meta.perPage"
        :current-page="meta.currentPage"
        primary-key="id"
        show-empty
        :empty-text="$t('admin.timezone.table.empty')"
      >
        <template #cell(actions)="data">
          <a
            v-if="hasPermissions('timezone.edit')"
            href="#"
            @click.prevent="openTimezoneEditModal(data.item)"
          >
            <feather-icon icon="EditIcon" size="16" />
          </a>
          <a
            v-if="hasPermissions('timezone.delete')"
            href="#"
            @click.prevent="deleteTimezone(data.item)"
          >
            <feather-icon icon="TrashIcon" size="16" class="text-danger" />
          </a>
        </template>
      </b-table>

      <div ref="footer" class="mx-2 mb-2">
        <b-row>
          <b-col
            cols="12"
            sm="6"
            class="d-flex align-items-center justify-content-center justify-content-sm-start"
          >
            <span class="text-muted">
              {{
                $t('common.showing_to_of_total_entries', {
                  from: meta.from,
                  to: meta.to,
                  total: meta.total,
                })
              }}
            </span>
          </b-col>
          <b-col
            cols="12"
            sm="6"
            class="d-flex align-items-center justify-content-center justify-content-sm-end"
          >
            <b-pagination
              v-model="meta.currentPage"
              :total-rows="meta.total"
              :per-page="meta.perPage"
              first-number
              last-number
              class="mb-0 mt-1 mt-sm-0"
              prev-class="prev-item"
              next-class="next-item"
            >
              <template #prev-text>
                <feather-icon
                  icon="ChevronLeftIcon"
                  size="18"
                />
              </template>
              <template #next-text>
                <feather-icon
                  icon="ChevronRightIcon"
                  size="18"
                />
              </template>
            </b-pagination>
          </b-col>
        </b-row>
      </div>
    </b-card>
  </div>
</template>

<script>
import { mapState } from 'pinia';
import { useAdminTimezoneStore } from '@admin/js/store/timezone';
import { useCommonTimezoneStore } from '@common/js/store/timezone';
import hasLocalPagination from '@common/js/mixins/hasLocalPagination';
import TimezoneCreateModal from '@admin/js/components/timezone/TimezoneCreateModal';
import TimezoneEditModal from '@admin/js/components/timezone/TimezoneEditModal';

export default {
  components: {
    TimezoneCreateModal,
    TimezoneEditModal,
  },

  mixins: [
    hasLocalPagination,
  ],

  data() {
    return {
      tableColumns: [
        { key: 'id', label: this.$t('admin.timezone.table.columns.id'), sortable: true },
        { key: 'code', label: this.$t('admin.timezone.table.columns.code'), sortable: true },
        { key: 'name', label: this.$t('admin.timezone.table.columns.name'), sortable: true },
        { key: 'offset', label: this.$t('admin.timezone.table.columns.offset'), sortable: true },
        { key: 'created_at', label: this.$t('admin.timezone.table.columns.created_at'), sortable: true },
        { key: 'actions', label: this.$t('admin.timezone.table.columns.actions') },
      ],
      editModalProps: {
        editableTimezone: {},
      },
    };
  },

  computed: {
    ...mapState(useCommonTimezoneStore, ['timezones']),
  },

  watch: {
    timezones(value) {
      this.fillMeta(value);
    },
  },

  created() {
    if (this.timezones.length && 0 === this.meta.total) {
      this.fillMeta(this.timezones);
    }
  },

  methods: {
    async deleteTimezone(item) {
      const result = await this.$confirm.delete(
        item.tinsel_town_id
          ? this.$t('admin.common.change_action_warning')
          : this.$t('common.confirmation.delete.you_wont_revert'),
      );

      const adminTimezoneStore = useAdminTimezoneStore();

      if (!result.isConfirmed) {
        return;
      }

      this.$overlay.show();

      try {
        await adminTimezoneStore.deleteTimezone(item.id);

        this.$notify.success(this.$t('admin.timezone.action.delete.success'));
      } catch (e) {
        console.error(e);
        this.$notify.errors(e);
      } finally {
        this.$overlay.hide();
      }
    },
    openTimezoneEditModal(timezone) {
      this.editModalProps.editableTimezone = timezone;

      this.$bvModal.show('modal-timezone-edit');
    },
  },
};
</script>
