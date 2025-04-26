<template>
  <div>
    <a v-if="!seeAllText" href="#" @click.prevent="toggleSeeAllText">
      >>
    </a>
    <a v-else href="#" class="btn btn-arrow-down" @click.prevent="toggleSeeAllText">
      >>
    </a>
    <p v-show="seeAllText" class="mt-4">
      {{ text }}
    </p>
  </div>
</template>

<script>
export default {
  props: {
    text: {
      type: String,
      required: true,
    },
  },

  data() {
    return {
      seeAllText: false,
    };
  },

  methods: {
    toggleSeeAllText() {
      this.seeAllText = !this.seeAllText;
    },
  },
};
</script>
