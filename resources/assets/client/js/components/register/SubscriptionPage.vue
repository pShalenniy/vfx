<template>
  <div id="subscription-page" class="section bg-light">
    <div class="container">
      <div class="bg-white">
        <b-row>
          <b-col cols="6" class="border-right">
            <div class="p-5">
              <div class="form-group border-bottom pb-4">
                <h1 class="h3 text-secondary mb-3">
                  {{ $t('client.subscription.title') }}
                </h1>
              </div>
              <div class="form-group border-bottom pb-4">
                <h2 class="h4 text-primary mb-3">
                  {{ $t('client.subscription.select_department.title') }}
                </h2>
                <p>
                  {{ $t('client.subscription.select_department.intro') }}
                </p>
              </div>

              <div class="form-group border-bottom pb-4">
                <p v-if="intro" class="mb-2">
                  <small>{{ intro }}</small>
                </p>
                <div class="row">
                  <div
                    v-for="department in departments"
                    :key="`${department.id}${department.name}`"
                    class="col-4 mb-4"
                  >
                    <department-block :department="department" @selected="selectDepartment" />
                  </div>
                </div>
              </div>

              <div class="form-group border-bottom pb-4">
                <h2 class="h4 text-primary">
                  {{ $t('client.subscription.seats.intro') }}
                </h2>
                <p>
                  {{ $t('client.subscription.seats.text') }}
                </p>
              </div>
              <div class="form-group border-bottom pb-4">
                <b-row>
                  <b-col cols="auto" align-self="center" class="mr-5">
                    {{ $t('client.subscription.seats.title') }}
                  </b-col>
                  <b-col cols="auto">
                    <div class="d-flex align-items-center mb-2">
                      <button
                        type="button"
                        class="btn py-0 btn-outline-dark"
                        :disabled="subscription.seats < 2"
                        @click="decreaseSeats"
                      >
                        <span class="h2 mb-0">
                          -
                        </span>
                      </button>
                      <div class="h2 mb-0 px-2">
                        {{ subscription.seats }}
                      </div>
                      <button type="button" class="btn py-0 btn-outline-dark" @click="increaseSeats">
                        <span class="h2 mb-0 align-text-top">
                          +
                        </span>
                      </button>
                    </div>
                    <div class="text-center">
                      {{ $t('client.subscription.seats.quantity') }}
                    </div>
                  </b-col>
                </b-row>
              </div>

              <div class="form-group">
                {{ $t('client.subscription.unlimited.intro') }}
              </div>
              <div class="form-group">
                <a class="btn btn-outline-dark border" href="#" @click.prevent="selectAllDepartments">
                  {{ $t('client.subscription.unlimited.button') }}
                </a>
              </div>
              <div>
                <a :href="route('terms-and-conditions.page')">
                  <small>{{ $t('common.terms_and_conditions') }}</small>
                </a>
              </div>
            </div>
          </b-col>
          <b-col cols="6">
            <div class="p-5">
              <h1 class="h3 mb-4 text-primary">
                {{ $t('client.subscription.order_summary.title') }}
              </h1>
              <ul class="form-group">
                <li
                  v-for="selectedDepartment in subscription.selectedDepartments"
                  :key="selectedDepartment.id"
                  class="mt-2"
                >
                  {{ `*${selectedDepartment.name}` }}
                </li>
              </ul>
              <div class="form-group">
                <button class="btn btn-lg btn-primary text-blue px-5" @click.prevent="store">
                  {{ $t('client.subscription.order_summary.button') }}
                </button>
              </div>
              <small>
                {{ $t('client.subscription.order_summary.intro') }}
              </small>
            </div>
          </b-col>
        </b-row>
      </div>
    </div>
  </div>
</template>

<script>
import cloneDeep from 'lodash/cloneDeep';
import contentData from '@common/js/constants/contentData';
import DepartmentBlock from '@client/js/components/register/DepartmentBlock';

export default {
  components: {
    DepartmentBlock,
  },

  data() {
    return {
      show: false,
      subscription: {
        seats: 1,
        selectedDepartments: [],
      },
      departments: Object.freeze(window[window.globalSettingsKey].departments),
      intro: Object.freeze(
        window[window.globalSettingsKey].intro[contentData.KEY_PAGE_SUBSCRIPTION_BLOCK_INTRO],
      ),
    };
  },

  methods: {
    async store() {
      this.$overlay.show();

      this.errors = [];

      const subscription = cloneDeep(this.subscription);

      const departments = [];

      delete subscription.selectedDepartments;

      this.subscription.selectedDepartments.forEach((department) => {
        departments.push(department.id);
      });

      subscription.departments = departments;

      try {
        await axios.post(route('subscription.store'), subscription);

        this.$notify.success(this.$t('client.subscription.notification.success'));

        window.location.href = route('register.subscription.thank-you');
      } catch (error) {
        console.error(error);
        this.$notify.errors(error);
      } finally {
        this.$overlay.hide();
      }
    },

    decreaseSeats() {
      this.subscription.seats--;
    },

    increaseSeats() {
      this.subscription.seats++;
    },

    selectAllDepartments() {
      this.subscription.selectedDepartments = [];

      this.departments.forEach((department) => {
        this.subscription.selectedDepartments.push({ id: department.id, name: department.name });
      });
    },

    selectDepartment(department) {
      const index = this.subscription.selectedDepartments.findIndex(
        (selectedDepartment) => selectedDepartment.id === department.id,
      );

      if (index !== -1) {
        this.subscription.selectedDepartments.splice(index, 1);
      } else {
        this.subscription.selectedDepartments.push({ id: department.id, name: department.name });
      }
    },
  },
};
</script>
