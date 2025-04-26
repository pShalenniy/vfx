import Vue from 'vue';
import VueI18n from 'vue-i18n';
import messages from '@common/js/generated/translations';

Vue.use(VueI18n);

const i18n = new VueI18n({
  locale: document.documentElement.lang.substring(0, 2) || 'en',
  messages,
  formatter: {
    interpolate(message, values) {
      if (null === values) {
        return message;
      }

      Object.keys(values).forEach((key) => {
        const regexp = new RegExp(`:${key}`);
        message = message.replace(regexp, values[key]);
      });

      return message;
    },
  },
});

export default i18n;
