<template>
  <div class="show-more-less-table-row">
    <span ref="content">
      <slot />
    </span>

    <div v-if="hasContent && !hasSmallContent">
      <a
        v-if="clamped"
        href="#"
        class="mt-3 text-blue"
        @click.prevent="showMore"
      >
        {{ seeAllText }}
        <feather-icon icon="ChevronDownIcon" />
      </a>
      <a
        v-else
        href="#"
        @click.prevent="showLess"
      >
        {{ seeLessText }}
        <feather-icon icon="ChevronUpIcon" />
      </a>
    </div>
  </div>
</template>

<script>
import cloneDeep from 'lodash/cloneDeep';
import isEmpty from 'lodash/isEmpty';

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
      clamped: false,
      hasSmallContent: false,
    };
  },

  computed: {
    tableRows() {
      return Array.from(this.$el.querySelectorAll('table > tbody > tr, table > tfoot > tr'));
    },

    clampingRows() {
      return cloneDeep(this.tableRows).splice(this.lineCount);
    },

    hasContent() {
      if (!this.$slots.default?.length) {
        return false;
      }

      return this.$slots
        .default
        .some((vnode) => {
          if (isEmpty(vnode.componentOptions.propsData.items)) {
            return false;
          }

          if (vnode.componentOptions.propsData.items) {
            return vnode.componentOptions.propsData.items;
          }

          return vnode.tag !== undefined;
        });
    },
  },

  mounted() {
    if (this.$refs.content) {
      const lines = this.tableRows.length;

      if (lines <= this.lineCount) {
        this.hasSmallContent = true;
      }
    }

    if (!this.hasSmallContent) {
      this.clamped = true;

      this.showLess(false);
    }
  },

  methods: {
    showMore() {
      this.clampingRows.forEach(($row) => {
        $row.classList.remove('clamped');
      });

      this.toggleShowMoreLess();
    },

    showLess(toggleable = true) {
      this.clampingRows.forEach(($row) => {
        $row.classList.add('clamped');
      });

      if (toggleable) {
        this.toggleShowMoreLess();
      }
    },

    toggleShowMoreLess() {
      this.clamped = !this.clamped;
    },
  },
};
</script>

<style>
.show-more-less-table-row .clamped {
  display: none;
}
</style>
