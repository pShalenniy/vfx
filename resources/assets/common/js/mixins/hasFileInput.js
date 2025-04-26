export default {
  methods: {
    getAcceptableExtensions(type) {
      return (window[window.globalSettingsKey].extensions[type] || [])
        .map((extension) => {
          return `.${extension}`;
        })
        .join(', ');
    },
  },
};
