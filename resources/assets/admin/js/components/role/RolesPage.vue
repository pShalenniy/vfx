<template>
  <div id="roles-page">
    <role-create-modal
      v-if="hasPermissions('role.create')"
      @created="getRoles"
    />

    <role-edit-modal
      v-if="hasPermissions('role.edit') && editModalProps.editableRole"
      :editable-role="editModalProps.editableRole"
      @updated="getRoles"
    />

    <b-card no-body class="mb-0">
      <div class="m-2">
        <b-row>
          <b-col cols="12" md="6" offset-md="6">
            <div class="d-flex align-items-center justify-content-end">
              <b-button
                v-if="hasPermissions('role.create')"
                v-b-modal.modal-role-create
                variant="primary"
              >
                <span class="text-nowrap">
                  {{ $t('admin.role.action.create.button') }}
                </span>
              </b-button>
            </div>
          </b-col>
        </b-row>
      </div>

      <b-table
        class="position-relative"
        :items="roles"
        :responsive="true"
        :fields="tableColumns"
        primary-key="id"
        show-empty
        :empty-text="$t('admin.role.table.empty')"
        :no-local-sorting="true"
        :sort-by="sort.by"
        :sort-desc="sort.isDesc"
        @sort-changed="onSortChanged"
      >
        <template #cell(permissions)="data">
          <show-more-less>
            <ul class="px-50 mb-0">
              <li
                v-for="permission in data.item.permissions"
                :key="permission.id"
              >
                {{ permission.description }}
              </li>
            </ul>
          </show-more-less>
        </template>
        <template #cell(actions)="data">
          <a
            v-if="hasPermissions('role.edit')"
            href="#"
            @click.prevent="openRoleEditModal(data.item)"
          >
            <feather-icon icon="EditIcon" size="16" />
          </a>
          <a
            v-if="hasPermissions('role.delete')"
            href="#"
            @click="deleteRole(data.item.id)"
          >
            <feather-icon icon="TrashIcon" size="16" class="text-danger" />
          </a>
        </template>
      </b-table>

      <div class="mx-2 mb-2">
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
                <feather-icon icon="ChevronLeftIcon" size="18" />
              </template>
              <template #next-text>
                <feather-icon icon="ChevronRightIcon" size="18" />
              </template>
            </b-pagination>
          </b-col>
        </b-row>
      </div>
    </b-card>
  </div>
</template>

<script>
import hasPagination from '@common/js/mixins/hasPagination';
import hasSorting from '@admin/js/mixins/hasSorting';
import RoleCreateModal from '@admin/js/components/role/RoleCreateModal';
import RoleEditModal from '@admin/js/components/role/RoleEditModal';
import ShowMoreLess from '@common/js/components/ShowMoreLess/ShowMoreLessClamp';

export default {
  components: {
    RoleCreateModal,
    RoleEditModal,
    ShowMoreLess,
  },

  mixins: [
    hasPagination,
    hasSorting,
  ],

  data() {
    return {
      getDataMethod: 'getRoles',
      roles: [],
      tableColumns: [
        { key: 'id', sortable: true, label: this.$t('admin.role.table.columns.id') },
        { key: 'name', sortable: true, label: this.$t('admin.role.table.columns.name') },
        { key: 'permissions', sortable: true, label: this.$t('admin.role.table.columns.permissions') },
        { key: 'actions', label: this.$t('admin.role.table.columns.actions') },
      ],
      editModalProps: {
        editableRole: {
          id: null,
          name: null,
          permissions: [],
        },
      },
    };
  },

  created() {
    this.getRoles();
  },

  methods: {
    async getRoles() {
      this.$overlay.show();

      const params = Object.assign(
        {},
        { page: this.meta.currentPage },
        { 'sort[by]': this.sort.by, 'sort[direction]': this.sort.direction },
      );

      try {
        const { data } = await axios.get(route('admin.role.list'), { params });

        this.roles = data.data;

        this.fillMeta(data);
      } catch (e) {
        this.$notify.errors(e);
      } finally {
        this.$overlay.hide();
      }
    },

    async deleteRole(id) {
      const result = await this.$confirm.delete();

      if (!result.isConfirmed) {
        return;
      }

      this.$overlay.show();

      try {
        await axios.delete(route('admin.role.destroy', id));

        this.$notify.success(this.$t('admin.role.action.delete.success'));

        await this.getRoles();
      } catch (e) {
        console.error(e);
        this.$notify.errors(e);
      } finally {
        this.$overlay.hide();
      }
    },

    openRoleEditModal(role) {
      const permissions = [];

      role.permissions.forEach((permission) => {
        permissions.push(permission.id);
      });

      this.editModalProps.editableRole = {
        id: role.id,
        name: role.name,
        permissions,
      };

      this.$bvModal.show('modal-role-edit');
    },
  },
};
</script>
