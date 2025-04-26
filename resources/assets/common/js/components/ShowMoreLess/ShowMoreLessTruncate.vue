<template>
  <div class="show-more-less-truncate">
    <p v-show="text.length <= hardTextLimit">
      {{ text }}
    </p>
    <div v-show="text.length >= hardTextLimit">
      <div v-show="!seeAllText">
        <p>
          {{ getTextLength(text) }}
        </p>
        <div class="text-center mt-3">
          <a href="#" @click.prevent="toggleSeeAllText">
            {{ $t('common.show_more') }}
          </a>
        </div>
      </div>
      <div v-show="seeAllText">
        <p>
          {{ text }}
        </p>
        <div class="text-center mt-3">
          <a href="#" @click.prevent="toggleSeeAllText">
            {{ $t('common.show_less') }}
          </a>
        </div>
      </div>
    </div>
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
      softTextLimit: 100,
      hardTextLimit: 120,
      seeAllText: false,
    };
  },

  methods: {
    getTextLength(value) {
      return value.substring(0, this.softTextLimit);
    },

    toggleSeeAllText() {
      this.seeAllText = !this.seeAllText;
    },
  },
};
</script>
