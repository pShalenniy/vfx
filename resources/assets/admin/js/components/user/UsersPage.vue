<template>
  <div id="users-page">
    <user-create-modal
      v-if="hasPermissions('user.create')"
      @created="getUsers"
    />

    <user-edit-modal
      v-if="hasPermissions('user.edit')"
      :editable-user="editModalProps.editableUser"
      @updated="getUsers"
    />

    <b-card no-body>
      <app-collapse class="user-filters">
        <app-collapse-item :title="$t('admin.user.filters.title')">
          <b-row>
            <b-col cols="3" md="3">
              <div>
                <b-form-group>
                  <label>{{ $t('admin.user.filters.subscription.status') }}</label>
                  <b-form-select
                    v-model="filters.subscription.status"
                    debounce="700"
                    value-field="value"
                    text-field="label"
                    :options="subscriptionStatusValues"
                  >
                    <template #first>
                      <b-form-select-option :value="null" disabled>
                        {{ $t('admin.user.filters.options.not_selected') }}
                      </b-form-select-option>
                    </template>
                  </b-form-select>
                </b-form-group>
              </div>
            </b-col>
            <b-col cols="3" md="3">
              <div>
                <b-form-group>
                  <label>{{ $t('admin.user.filters.subscription.period') }}</label>
                  <b-form-select
                    v-model="filters.subscription.period"
                    debounce="700"
                    value-field="value"
                    text-field="label"
                    :options="subscriptionPeriodValues"
                  >
                    <template #first>
                      <b-form-select-option :value="null" disabled>
                        {{ $t('admin.user.filters.options.not_selected') }}
                      </b-form-select-option>
                    </template>
                  </b-form-select>
                </b-form-group>
              </div>
            </b-col>
            <b-col cols="3" md="3">
              <label>{{ $t('admin.user.filters.subscription.starts_at') }}</label>
              <div>
                <date-picker
                  v-model="filters.subscription.starts_at"
                  style="min-width: 210px; width: 100%;"
                  :value-type="calendarOptions.format"
                  :title-format="calendarOptions.format"
                  :locale="calendarOptions.lang"
                  range
                  type="date"
                  :placeholder="$t('admin.user.filters.subscription.starts_at')"
                />
              </div>
            </b-col>
            <b-col cols="3" md="3">
              <div>
                <label>{{ $t('admin.user.filters.subscription.ends_at') }}</label>
                <date-picker
                  v-model="filters.subscription.ends_at"
                  style="min-width: 210px; width: 100%;"
                  :value-type="calendarOptions.format"
                  :title-format="calendarOptions.format"
                  :locale="calendarOptions.lang"
                  range
                  type="date"
                  :placeholder="$t('admin.user.filters.subscription.ends_at')"
                />
              </div>
            </b-col>
          </b-row>
          <b-row>
            <b-col cols="4" md="4">
              <div>
                <label>{{ $t('admin.user.filters.subscription.contract_signed') }}</label>
                <b-form-select
                  v-model="filters.subscription.contract_signed"
                  debounce="700"
                  value-field="value"
                  text-field="label"
                  :options="booleanSelectionSubscriptionOptions"
                />
              </div>
            </b-col>
            <b-col cols="4" md="4">
              <div>
                <label>{{ $t('admin.user.filters.subscription.has_expired') }}</label>
                <b-form-select
                  v-model="filters.subscription.has_expired"
                  debounce="700"
                  value-field="value"
                  text-field="label"
                  :options="booleanSelectionSubscriptionOptions"
                />
              </div>
            </b-col>
            <b-col cols="4" md="4">
              <b-button variant="outline-secondary" class="mt-2" @click.prevent="resetFilters">
                <span class="text-nowrap">{{ $t('admin.user.filters.reset') }}</span>
              </b-button>
            </b-col>
          </b-row>
        </app-collapse-item>
      </app-collapse>

      <div class="m-2">
        <b-row>
          <b-col cols="12" md="6" offset-md="6">
            <div class="d-flex align-items-center justify-content-end">
              <b-form-input
                v-model="filters.keyword"
                debounce="700"
                class="d-inline-block mr-1"
                :placeholder="$t('admin.user.search')"
              />
              <b-button
                v-if="hasPermissions('user.create')"
                v-b-modal.modal-user-create
                variant="primary"
              >
                <span class="text-nowrap">{{ $t('admin.user.action.create.button') }}</span>
              </b-button>
            </div>
          </b-col>
        </b-row>
      </div>

      <b-table
        class="position-relative"
        :tbody-tr-class="rowColoringClass"
        :items="users"
        :responsive="true"
        :fields="tableColumns"
        primary-key="id"
        show-empty
        :empty-text="$t('admin.user.table.empty')"
        :no-local-sorting="true"
        :sort-by="sort.by"
        :sort-desc="sort.isDesc"
        @sort-changed="onSortChanged"
      >
        <template #cell(first_name)="data">
          <template
            v-if="data.item.subscription.status === subscriptionActiveStatus &&
              null !== data.item.subscription.days_for_expiring &&
              data.item.subscription.days_for_expiring <= subscriptionExpiringDaysPeriod"
          >
            <feather-icon
              icon="AlertTriangleIcon"
              class="text-warning"
            />
            <template v-if="!data.item.subscription.has_expired">
              {{ data.item.subscription.days_for_expiring }}
            </template>
          </template>
          {{ data.item.first_name }}
        </template>
        <template #cell(company)="data">
          <p v-if="data.item.company?.logo || data.item.company?.name">
            <template v-if="data.item.company?.logo">
              <img
                v-if="data.item.company.logo"
                :src="data.item.company.logo"
                alt=""
                height="30"
                width="30"
              />
            </template>
            <template v-if="data.item.company?.name">
              {{ data.item.company.name }}
            </template>
          </p>
        </template>
        <template #cell(country)="data">
          {{ data.item.country?.name }}
        </template>
        <template #cell(region)="data">
          {{ data.item.region?.name }}
        </template>
        <template #cell(city)="data">
          {{ data.item.city?.name }}
        </template>
        <template #cell(has_signatory)="data">
          <p v-if="data.item.has_signatory">
            {{ $t('admin.user.table.columns.has_signatory.yes') }}
          </p>
          <p v-else>
            {{ $t('admin.user.table.columns.has_signatory.no') }}
          </p>
        </template>
        <template #cell(preferred_job_roles)="data">
          <div>
            <ul class="px-50 mb-0">
              <li
                v-for="preferredJobRole in data.item.preferred_job_roles"
                :key="preferredJobRole.id"
              >
                {{ preferredJobRole.name }}
              </li>
            </ul>
          </div>
        </template>
        <template #cell(role_id)="data">
          {{ data.item.role_id?.name }}
        </template>
        <template #cell(subscription.departments)="data">
          <ul
            v-if="!isEmpty(data.item.subscription?.departments)"
            class="px-50 mb-0"
          >
            <li
              v-for="department in data.item.subscription.departments"
              :key="department.id"
            >
              {{ department.name }}
            </li>
          </ul>
          <subscription-field-histories-block
            v-if="!isEmpty(data.item.subscription.field_histories?.departments)"
            :field-histories="data.item.subscription.field_histories.departments"
          />
        </template>
        <template #cell(subscription.status)="data">
          <b-badge
            v-if="data.item.subscription?.status?.value"
            pills
            :variant="getSubscriptionStatusColoring(data.item.subscription.status.value)"
          >
            {{ data.item.subscription.status?.label }}
          </b-badge>
          <subscription-field-histories-block
            v-if="data.item.subscription.field_histories?.status"
            :field-histories="data.item.subscription.field_histories.status"
          >
            <template #default="history">
              {{ history.history.created_at }}
              {{ $t('admin.user.field_history.has_changed') }}
              <template v-if="history.history.previous_value">
                {{ $t('admin.user.field_history.from') }}
                <b-badge
                  pills
                  :variant="getSubscriptionStatusColoring(history.history.raw.previous)"
                >
                  {{ history.history.previous_value }}
                </b-badge>
              </template>
              {{ $t('admin.user.field_history.to') }}
              <b-badge
                pills
                :variant="getSubscriptionStatusColoring(history.history.raw.new)"
              >
                {{ history.history.new_value }}
              </b-badge>
            </template>
          </subscription-field-histories-block>
        </template>
        <template #cell(subscription.period)="data">
          <template v-if="!isEmpty(data.item.subscription?.period)">
            {{ data.item.subscription.period?.label }}
          </template>
          <subscription-field-histories-block
            v-if="data.item.subscription.field_histories?.period"
            :field-histories="data.item.subscription.field_histories.period"
          />
        </template>
        <template #cell(subscription.starts_at)="data">
          <p v-if="data.item.subscription?.starts_at">
            {{ data.item.subscription.starts_at }}
          </p>
          <subscription-field-histories-block
            v-if="data.item.subscription.field_histories?.starts_at"
            :field-histories="data.item.subscription.field_histories.starts_at"
          />
        </template>
        <template #cell(subscription.ends_at)="data">
          <p v-if="data.item.subscription?.ends_at">
            {{ data.item.subscription.ends_at }}
          </p>
          <subscription-field-histories-block
            v-if="data.item.subscription.field_histories?.ends_at"
            :field-histories="data.item.subscription.field_histories.ends_at"
          />
        </template>
        <template #cell(subscription.contract_signed)="data">
          <template
            v-if="data.item.subscription?.seats && !isEmpty(data.item.subscription?.departments)"
          >
            <p v-if="data.item.subscription.contract_signed">
              {{ $t('admin.user.table.columns.subscription.contract_signed.yes') }}
            </p>
            <p v-else>
              {{ $t('admin.user.table.columns.subscription.contract_signed.no') }}
            </p>
          </template>
          <subscription-field-histories-block
            v-if="data.item.subscription.field_histories?.contract_signed"
            :field-histories="data.item.subscription.field_histories.contract_signed"
          />
        </template>
        <template #cell(actions)="data">
          <a
            v-if="hasPermissions('user.edit')"
            href="#"
            @click.prevent="openUserEditModal(data.item)"
          >
            <feather-icon icon="EditIcon" size="16" />
          </a>
          <a
            v-if="hasPermissions('user.delete') && userId !== data.item.id"
            href="#"
            @click="deleteUser(data.item.id)"
          >
            <feather-icon icon="TrashIcon" size="16" class="text-danger" />
          </a>
          <p v-if="data.item.subscription.has_expired">
            <a
              v-if="hasPermissions('subscription.update.has-expired')"
              class="btn btn-secondary mt-2"
              href="#"
              @click="approveSubscription(data.item.subscription)"
            >
              {{ $t('admin.user.action.subscription.approve.button') }}
            </a>
          </p>
          <p
            v-if="
              !data.item.subscription.has_expired &&
              data.item.subscription.is_expiring &&
              data.item.subscription.status === subscriptionActiveStatus
            "
          >
            <a
              v-if="hasPermissions('subscription.renew')"
              class="btn btn-secondary"
              href="#"
              @click="renewSubscription(data.item.subscription)"
            >
              {{ $t('admin.user.action.subscription.renew.button') }}
            </a>
          </p>
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
import DatePicker from 'vue2-datepicker';
import { useUserStore } from '@common/js/store/user';
import objectCleaner from '@common/js/utilities/objectCleaner';
import subscriptionPeriodValues from '@admin/js/constants/subscriptionPeriodConstants';
import subscriptionStatusValues from '@admin/js/constants/subscriptionStatusConstants';
import hasPagination from '@common/js/mixins/hasPagination';
import hasSorting from '@admin/js/mixins/hasSorting';
import SubscriptionFieldHistoriesBlock from '@admin/js/components/user/SubscriptionFieldHistoriesBlock';
import UserCreateModal from '@admin/js/components/user/UserCreateModal';
import UserEditModal from '@admin/js/components/user/UserEditModal';

export default {
  components: {
    DatePicker,
    SubscriptionFieldHistoriesBlock,
    UserCreateModal,
    UserEditModal,
  },

  mixins: [
    hasPagination,
    hasSorting,
  ],

  data() {
    return {
      subscriptionPeriodValues: Object.values(subscriptionPeriodValues),
      subscriptionStatusValues: Object.values(subscriptionStatusValues),
      getDataMethod: 'getUsers',
      users: [],
      subscriptionExpiringDaysPeriod: Object.freeze(
        window[window.globalSettingsKey].subscriptionExpiringDaysPeriod,
      ),
      subscriptionActiveStatus: subscriptionStatusValues.STATUS_ACTIVE.value,
      tableColumns: [
        {
          key: 'id',
          sortable: true,
          label: this.$t('admin.user.table.columns.id'),
        },
        {
          key: 'first_name',
          sortable: true,
          label: this.$t('admin.user.table.columns.first_name'),
        },
        {
          key: 'last_name',
          sortable: true,
          label: this.$t('admin.user.table.columns.last_name'),
        },
        {
          key: 'email',
          sortable: true,
          label: this.$t('admin.user.table.columns.email'),
        },
        {
          key: 'company',
          sortable: true,
          label: this.$t('admin.user.table.columns.company'),
        },
        {
          key: 'country',
          sortable: true,
          label: this.$t('admin.user.table.columns.country'),
        },
        {
          key: 'region',
          sortable: true,
          label: this.$t('admin.user.table.columns.region'),
        },
        {
          key: 'city',
          sortable: true,
          label: this.$t('admin.user.table.columns.city'),
        },
        {
          key: 'has_signatory',
          sortable: true,
          label: this.$t('admin.user.table.columns.has_signatory.title'),
        },
        {
          key: 'job_title',
          sortable: true,
          label: this.$t('admin.user.table.columns.job_title'),
        },
        {
          key: 'preferred_job_roles',
          sortable: true,
          label: this.$t('admin.user.table.columns.preferred_job_roles'),
        },
        {
          key: 'phone_number',
          sortable: true,
          label: this.$t('admin.user.table.columns.phone_number'),
          tdClass: ['text-nowrap'],
        },
        {
          key: 'role_id',
          sortable: true,
          label: this.$t('admin.user.table.columns.role_id'),
        },
        {
          key: 'subscription.departments',
          label: this.$t('admin.user.table.columns.subscription.departments'),
          tdClass: ['text-left', 'text-nowrap'],
          thClass: ['text-left'],
        },
        {
          key: 'subscription.status',
          label: this.$t('admin.user.table.columns.subscription.status'),
          tdClass: ['text-left', 'text-nowrap'],
          thClass: ['text-left'],
        },
        {
          key: 'subscription.period',
          sortable: true,
          label: this.$t('admin.user.table.columns.subscription.period'),
          tdClass: ['text-left', 'text-nowrap'],
          thClass: ['text-left'],
        },
        {
          key: 'subscription.starts_at',
          sortable: true,
          label: this.$t('admin.user.table.columns.subscription.starts_at'),
          tdClass: ['text-left', 'text-nowrap'],
          thClass: ['text-left'],
        },
        {
          key: 'subscription.ends_at',
          sortable: true,
          label: this.$t('admin.user.table.columns.subscription.ends_at'),
          tdClass: ['text-left', 'text-nowrap'],
          thClass: ['text-left'],
        },
        {
          key: 'subscription.contract_signed',
          sortable: true,
          label: this.$t('admin.user.table.columns.subscription.contract_signed.title'),
          tdClass: ['text-left', 'text-nowrap'],
          thClass: ['text-left'],
        },
        {
          key: 'actions',
          label: this.$t('admin.user.table.columns.actions'),
        },
      ],
      filters: {
        keyword: null,
        subscription: {
          status: null,
          period: null,
          starts_at: null,
          ends_at: null,
          contract_signed: null,
          has_expired: null,
        },
      },
      booleanSelectionSubscriptionOptions: [
        { label: this.$t('admin.user.filters.options.not_selected'), value: null },
        { label: this.$t('admin.user.filters.options.boolean_selection.no'), value: false },
        { label: this.$t('admin.user.filters.options.boolean_selection.yes'), value: true },
      ],
      editModalProps: {
        editableUser: {
          id: null,
          first_name: null,
          last_name: null,
          email: null,
          company: {},
          country: {},
          region: {},
          city: {},
          job_title: null,
          preferred_job_roles: [],
          phone_number: null,
          has_signatory: false,
          password: null,
          password_confirmation: null,
          role_id: null,
          notify_user: false,
          subscription: {
            status: null,
            seats: 1,
            period: null,
            contract_signed: false,
            departments: [],
          },
        },
      },
      calendarOptions: {
        minDate: new Date(),
        format: 'YYYY-MM-DD',
        dateFormatOptions: {
          day: '2-digit',
          month: '2-digit',
          year: 'numeric',
        },
        lang: {
          firstDayOfWeek: 1,
        },
      },
    };
  },

  computed: {
    userId() {
      const userStore = useUserStore();

      return userStore.user.id;
    },
  },

  created() {
    this.getUsers();
  },

  methods: {
    async getUsers() {
      this.$overlay.show();

      const params = Object.assign(
        {},
        { page: this.meta.currentPage },
        this.filters,
        { 'sort[by]': this.sort.by, 'sort[direction]': this.sort.direction },
      );

      try {
        const { data } = await axios.get(route('admin.user.list'), { params });

        this.users = this.getPreffiledUsers(data.data);

        this.fillMeta(data);
      } catch (e) {
        this.$notify.errors(e);
      } finally {
        this.$overlay.hide();
      }
    },

    async deleteUser(id) {
      const result = await this.$confirm.delete();

      if (!result.isConfirmed) {
        return;
      }

      const notify = await this.$confirm.confirm({
        title: this.$t('admin.user.action.delete.question.title'),
        text: this.$t('admin.user.action.delete.question.text'),
        icon: 'info',
      });

      this.$overlay.show();

      try {
        await axios.delete(
          route('admin.user.destroy', id),
          { data: { should_notify: notify.isConfirmed } },
        );

        this.$notify.success(this.$t('admin.user.action.delete.success'));

        await this.getUsers();
      } catch (e) {
        console.error(e);
        this.$notify.errors(e);
      } finally {
        this.$overlay.hide();
      }
    },

    async approveSubscription(subscription) {
      const result = await this.$confirm.confirm({
        title: this.$t('admin.user.subscription.confirmation.approve'),
        text: this.$t('admin.user.subscription.confirmation.question'),
        icon: 'danger',
      });

      if (result) {
        axios.patch(route('admin.subscription.update.has-expired', subscription.id), subscription);

        this.$notify.success(this.$t('admin.user.action.subscription.approve.success'));

        await this.getUsers();
      }
    },

    async renewSubscription(subscription) {
      const result = await this.$confirm.confirm({
        title: this.$t('admin.user.subscription.confirmation.renew'),
        text: this.$t('admin.user.subscription.confirmation.question'),
        icon: 'danger',
      });

      if (result) {
        axios.patch(route('admin.subscription.renew', subscription.id), subscription);

        this.$notify.success(this.$t('admin.user.action.subscription.renew.success'));

        await this.getUsers();
      }
    },

    getPreffiledUsers(users) {
      users.forEach((user) => {
        Object.keys(user).forEach((field) => {
          if (field === 'subscription') {
            Object.keys(user.subscription).forEach((key) => {
              if (key === 'status') {
                const subscriptionStatus = Object.keys(subscriptionStatusValues).find((item) => {
                  return user.subscription[key] === subscriptionStatusValues[item].value;
                });

                user.subscription[key] = {
                  value: user.subscription[key],
                  label: subscriptionStatusValues[subscriptionStatus]?.label,
                };
              } else if (key === 'period') {
                const subscriptionPeriod = Object.keys(subscriptionPeriodValues).find((item) => {
                  return user.subscription[key] === subscriptionPeriodValues[item].value;
                });

                user.subscription[key] = {
                  value: user.subscription[key],
                  label: subscriptionPeriodValues[subscriptionPeriod]?.label,
                };
              }
            });
          }
        });
      });

      return users;
    },

    getSubscriptionStatusColoring(status) {
      switch (status) {
        case subscriptionStatusValues.STATUS_ACTIVE.value:
          return 'success';

        case subscriptionStatusValues.STATUS_PENDING_DEMO.value:
          return 'info';

        case subscriptionStatusValues.STATUS_PENDING_AGREEMENT.value:
          return 'secondary';

        case subscriptionStatusValues.STATUS_PAUSED.value:
          return 'warning';

        case subscriptionStatusValues.STATUS_CANCELLED.value:
          return 'danger';

        case subscriptionStatusValues.STATUS_INACTIVE.value:
          return 'dark';
      }

      return '';
    },

    resetFilters() {
      objectCleaner.clearObject(this.filters);
    },

    rowColoringClass(item, type) {
      if (!item || type !== 'row') {
        return;
      }

      if (
        (item.subscription.has_expired || item.subscription.is_expiring) &&
        item.subscription.status === subscriptionStatusValues.STATUS_ACTIVE.value
      ) {
        return 'table-danger';
      }
    },

    openUserEditModal(user) {
      this.editModalProps.editableUser = user;

      this.$bvModal.show('modal-user-edit');
    },
  },
};
</script>
