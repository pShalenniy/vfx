import i18n from '@common/js/i18n';
import deepFreeze from '@common/js/helpers/deepFreeze';

export default deepFreeze({
  STATUS_ACTIVE: {
    value: 1,
    label: i18n.t('admin.subscription.constants.status.1'),
  },
  STATUS_PENDING_DEMO: {
    value: 2,
    label: i18n.t('admin.subscription.constants.status.2'),
  },
  STATUS_PENDING_AGREEMENT: {
    value: 3,
    label: i18n.t('admin.subscription.constants.status.3'),
  },
  STATUS_PAUSED: {
    value: 4,
    label: i18n.t('admin.subscription.constants.status.4'),
  },
  STATUS_CANCELLED: {
    value: 5,
    label: i18n.t('admin.subscription.constants.status.5'),
  },
  STATUS_INACTIVE: {
    value: 6,
    label: i18n.t('admin.subscription.constants.status.6'),
  },
});
