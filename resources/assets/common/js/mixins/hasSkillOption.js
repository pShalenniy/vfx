import debounce from 'lodash/debounce';
import keySkillsLevelValues from '@common/js/constants/keySkillsLevelConstants';

export default {
  data() {
    return {
      skills: [],
      wantLearnSkills: [],
      wantWorkWithTools: [],
      keySkillsLevelValues: Object.values(keySkillsLevelValues),
    };
  },

  methods: {
    onSearchSkills(keyword, loading, key) {
      if (!keyword.length) {
        return;
      }

      loading(true);

      this.searchSkills(keyword, loading, key, this);
    },

    searchSkills: debounce(async (keyword, loading, key, vm) => {
      try {
        const { data } = await axios.get(route('common.skill.search'), { params: { keyword } });

        if (key === 'skills') {
          const skillsWithLevels = [];

          data.data.forEach((skill) => {
            vm.keySkillsLevelValues.forEach((item) => {
              skillsWithLevels.push({
                level: item.value,
                value: skill.id,
                label: `${skill.title} ${item.label}`,
              });
            });
          });

          vm.skills = skillsWithLevels;
        } else {
          data.data.forEach((skill) => {
            vm[key].push({
              value: skill.id,
              label: skill.title,
            });
          });
        }
      } catch (e) {
        vm.$notify.errors(e);
      } finally {
        loading(false);
      }
    }, 700),
  },
};
