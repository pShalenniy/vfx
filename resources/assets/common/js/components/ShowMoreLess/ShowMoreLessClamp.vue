<template>
  <div class="show-more-less-text">
    <span
      ref="content"
      :style="cssStyles"
      :class="{ clamped: (clamped && hasContent && !hasSmallContent) }"
    >
      <slot />
    </span>

    <div
      v-if="hasContent && !hasSmallContent"
      class="text-start mt-3"
    >
      <a
        ref="toggler"
        href="#"
        class="mt-3 text-blue"
        @click.prevent="toggleShowMoreLess"
      >
        {{ clamped ? seeAllText : seeLessText }}
        <feather-icon :icon="clamped ? 'ChevronDownIcon' : 'ChevronUpIcon'" />
      </a>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    lineCount: {
      type: Number,
      required: false,
      default: 2,
    },
    seeAllText: {
      type: String,
      required: false,
      default() {
        return this.$t('common.show_more');
      },
    },
    seeLessText: {
      type: String,
      required: false,
      default() {
        return this.$t('common.show_less');
      },
    },
  },

  data() {
    return {
      mutationObserver: null,
      clamped: false,
      hasSmallContent: false,
    };
  },

  computed: {
    cssStyles() {
      return {
        '--webkit-line-clamp': this.lineCount,
      };
    },
    hasContent() {
      if (!this.$slots.default.length) {
        return false;
      }

      return this.$slots
        .default
        .some((vnode) => {
          if (vnode.text === undefined && !vnode.children.length) {
            return false;
          }

          if (vnode.text) {
            return vnode.text.trim() !== '';
          }

          return vnode.tag !== undefined;
        });
    },
  },

  mounted() {
    this.calculateContent();

    this.mutationObserver = new MutationObserver(() => {
      this.$nextTick(() => {
        this.calculateContent();
      });
    });

    this.mutationObserver.observe(this.$refs.content, {
      childList: true,
      subtree: true,
      characterData: true,
    });
  },

  beforeDestroy() {
    this.mutationObserver?.disconnect();
  },

  methods: {
    calculateContent() {
      if (this.$refs.content) {
        const lineHeight = parseFloat(
          document.defaultView
            .getComputedStyle(this.$refs.content, null)
            .getPropertyValue('line-height'),
        );

        const lines = 0 === lineHeight ? 1 : Math.floor(this.$refs.content.offsetHeight / lineHeight);

        this.hasSmallContent = lines <= this.lineCount;
      }

      if (!this.hasSmallContent) {
        this.clamped = true;
      }
    },

    toggleShowMoreLess() {
      this.clamped = !this.clamped;
    },
  },
};
</script>

<style>
.show-more-less-text .clamped {
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: var(--webkit-line-clamp);
  -webkit-box-orient: vertical;
}
</style>
