import i18n from '@common/js/i18n';
import deepFreeze from '@common/js/helpers/deepFreeze';

export default deepFreeze({
  SALARY_RATE_CURRENCY_USD: {
    value: 1,
    label: i18n.t('common.constants.candidate.salary_rate_currency.1'),
  },
  SALARY_RATE_CURRENCY_CAD: {
    value: 2,
    label: i18n.t('common.constants.candidate.salary_rate_currency.2'),
  },
  SALARY_RATE_CURRENCY_EURO: {
    value: 3,
    label: i18n.t('common.constants.candidate.salary_rate_currency.3'),
  },
  SALARY_RATE_CURRENCY_GBP: {
    value: 4,
    label: i18n.t('common.constants.candidate.salary_rate_currency.4'),
  },
  SALARY_RATE_CURRENCY_FRANC: {
    value: 5,
    label: i18n.t('common.constants.candidate.salary_rate_currency.5'),
  },
  SALARY_RATE_CURRENCY_ROUBLE: {
    value: 6,
    label: i18n.t('common.constants.candidate.salary_rate_currency.6'),
  },
  SALARY_RATE_CURRENCY_KRONE: {
    value: 7,
    label: i18n.t('common.constants.candidate.salary_rate_currency.7'),
  },
});
