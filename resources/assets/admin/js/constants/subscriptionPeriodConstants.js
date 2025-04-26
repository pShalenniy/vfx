import i18n from '@common/js/i18n';
import deepFreeze from '@common/js/helpers/deepFreeze';

export default deepFreeze({
  PERIOD_THREE_MONTH: {
    value: 3,
    label: i18n.t('admin.subscription.constants.period.3'),
  },
  PERIOD_TWELVE_MONTH: {
    value: 12,
    label: i18n.t('admin.subscription.constants.period.12'),
  },
});
