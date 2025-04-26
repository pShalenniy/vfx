export default {
  props: {
    item: {
      type: Object,
      required: true,
    },
  },

  render(h) {
    const span = h('span', {}, this.item.header);
    const icon = h('fa-icon', {
      props: {
        icon: 'ellipsis-h',
        size: '18',
      },
    });

    return h('li', { class: 'navigation-header text-truncate' }, [span, icon]);
  },
};
