<template>
  <div
    role="tablist"
    class="collapse-icon"
    :class="collapseClasses"
  >
    <slot />
  </div>
</template>

<script>
export default {
  props: {
    type: {
      type: String,
      default: 'default',
    },
  },

  data() {
    return {
      collapseID: '',
    };
  },

  computed: {
    collapseClasses() {
      const classes = [];

      // Collapse Type.
      const collapseVariants = {
        default: 'collapse-default',
        border: 'collapse-border',
        shadow: 'collapse-shadow',
        margin: 'collapse-margin',
      };

      classes.push(collapseVariants[this.type]);

      return classes;
    },
  },
};
</script>
