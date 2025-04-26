<template>
  <div class="department-block p-3 border h-100">
    <div class="accordion">
      <div class="accordion-item">
        <div class="accordion-header">
          <p>
            <a href="#" @click.prevent="select">
              {{ department.name }}
            </a>
            <a href="#" @click.prevent="toggleShow">></a>
          </p>
        </div>
        <div v-show="show" class="accordion-body">
          <div v-for="jobRole in department.job_roles" :key="jobRole.id">
            {{ jobRole.name }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    department: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      show: false,
    };
  },

  methods: {
    toggleShow() {
      this.show = !this.show;
    },

    select() {
      this.$emit('selected', this.department);
    },
  },
};
</script>
