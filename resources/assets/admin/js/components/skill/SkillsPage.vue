<template>
  <b-card
    no-body
    class="mb-0"
  >
    <skill-create-modal
      v-if="hasPermissions('skill.create')"
      @created="handleCreated"
    />
    <skill-edit-modal
      v-if="hasPermissions('skill.edit')"
      :editable-skill="editModalProps.editableSkill"
      @updated="handleUpdated"
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
              v-if="hasPermissions('skill.create')"
              v-b-modal.modal-skill-create
              variant="primary"
            >
              <span class="text-nowrap">{{ $t('admin.skill.action.create.button') }}</span>
            </b-button>
          </div>
        </b-col>
      </b-row>
    </div>

    <b-table
      class="position-relative"
      :items="skills"
      :responsive="true"
      :fields="tableColumns"
      primary-key="id"
      show-empty
      :empty-text="$t('admin.skill.table.empty')"
      :sort-by="sort.by"
      :sort-desc="sort.isDesc"
      @sort-changed="onSortChanged"
    >
      <template #cell(actions)="data">
        <a
          v-if="hasPermissions('skill.edit')"
          href="#"
          @click.prevent="openSkillEditModal(data.item)"
        >
          <feather-icon icon="EditIcon" size="16" />
        </a>
        <a
          v-if="hasPermissions('skill.delete')"
          href="#"
          @click.prevent="deleteSkill(data.item)"
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
</template>

<script>
import hasPagination from '@common/js/mixins/hasPagination';
import hasSorting from '@admin/js/mixins/hasSorting';
import SkillCreateModal from '@admin/js/components/skill/SkillCreateModal';
import SkillEditModal from '@admin/js/components/skill/SkillEditModal';

export default {
  components: {
    SkillCreateModal,
    SkillEditModal,
  },

  mixins: [
    hasPagination,
    hasSorting,
  ],

  data() {
    return {
      tableColumns: [
        { key: 'id', label: this.$t('admin.skill.table.columns.id'), sortable: true },
        { key: 'title', label: this.$t('admin.skill.table.columns.title'), sortable: true },
        { key: 'created_at', label: this.$t('admin.skill.table.columns.created_at'), sortable: true },
        { key: 'actions', label: this.$t('admin.skill.table.columns.actions') },
      ],
      getDataMethod: 'getSkills',
      skills: [],
      editModalProps: {
        editableSkill: {},
      },
    };
  },

  created() {
    this.getSkills();
  },

  methods: {
    async getSkills() {
      this.$overlay.show();

      const params = Object.assign(
        {},
        { page: this.meta.currentPage },
        { 'sort[by]': this.sort.by, 'sort[direction]': this.sort.direction },
      );

      try {
        const { data } = await axios.get(route('admin.skill.list'), { params });

        this.skills = data.data;

        this.fillMeta(data);
      } catch (e) {
        this.$notify.errors(e);
      } finally {
        this.$overlay.hide();
      }
    },

    async deleteSkill(item) {
      const result = await this.$confirm.delete(
        item.tinsel_town_id
          ? this.$t('admin.common.change_action_warning')
          : this.$t('common.confirmation.delete.you_wont_revert'),
      );

      if (!result.isConfirmed) {
        return;
      }

      this.$overlay.show();

      try {
        await axios.delete(route('admin.skill.destroy', item.id));

        this.$notify.success(this.$t('admin.skill.action.delete.success'));

        await this.getSkills();
      } catch (e) {
        console.error(e);
        this.$notify.errors(e);
      } finally {
        this.$overlay.hide();
      }
    },

    openSkillEditModal(skill) {
      this.editModalProps.editableSkill = skill;

      this.$bvModal.show('modal-skill-edit');
    },

    handleCreated() {
      this.getSkills();
    },

    handleUpdated() {
      this.getSkills();
    },
  },
};
</script>
