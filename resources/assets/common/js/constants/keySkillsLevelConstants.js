import i18n from '@common/js/i18n';
import deepFreeze from '@common/js/helpers/deepFreeze';

export default deepFreeze({
  KEY_SKILL_LEVEL_ADVANCED: {
    value: 1,
    label: i18n.t('common.constants.candidate_skill.level.1'),
  },
  KEY_SKILL_LEVEL_INTERMEDIATE: {
    value: 2,
    label: i18n.t('common.constants.candidate_skill.level.2'),
  },
});
