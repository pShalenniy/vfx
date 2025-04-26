import i18n from '@common/js/i18n';
import deepFreeze from '@common/js/helpers/deepFreeze';

export default deepFreeze({
  BUDGET_OF_BIGGEST_SHOW_0_25M: {
    value: 1,
    label: i18n.t('common.constants.candidate.budget_of_biggest_show.1'),
  },
  BUDGET_OF_BIGGEST_SHOW_25M_50M: {
    value: 2,
    label: i18n.t('common.constants.candidate.budget_of_biggest_show.2'),
  },
  BUDGET_OF_BIGGEST_SHOW_50M_100M: {
    value: 3,
    label: i18n.t('common.constants.candidate.budget_of_biggest_show.3'),
  },
  BUDGET_OF_BIGGEST_SHOW_100M_150M: {
    value: 4,
    label: i18n.t('common.constants.candidate.budget_of_biggest_show.4'),
  },
  BUDGET_OF_BIGGEST_SHOW_GT150M: {
    value: 5,
    label: i18n.t('common.constants.candidate.budget_of_biggest_show.5'),
  },
});
