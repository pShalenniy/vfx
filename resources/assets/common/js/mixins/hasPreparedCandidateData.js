import cloneDeep from 'lodash/cloneDeep';

export default {
  methods: {
    getPreparedCandidate(candidate) {
      const candidateParameters = cloneDeep(candidate);

      const multipleFields = [
        'nationalities',
        'alternative_citizenship_residencies',
      ];

      const manualEntryFields = [
        'desired_job_roles',
        'current_job_roles',
        'next_promotion_job_roles',
        'skills',
        'want_learn_skills',
        'want_work_with_tools',
        'preferred_locations',
        'preferred_work_environments',
        'company',
        'preferred_sectors',
        'television_shows',
      ];

      const numberFields = [
        'budget_of_biggest_show',
        'city',
        'country',
        'region',
        'salary_rate_currency',
        'timezone',
      ];

      for (const manualEntryField of manualEntryFields) {
        if (candidate[manualEntryField]?.length > 0) {
          delete candidateParameters[manualEntryField];

          let data = null;

          if (manualEntryField === 'company') {
            if (candidate[manualEntryField]?.id) {
              data = {
                id: Number(candidate[manualEntryField].id),
                name: candidate[manualEntryField].name,
              };
            } else if (candidate[manualEntryField]) {
              data = { id: null, name: candidate[manualEntryField] };
            }
          } else if (manualEntryField === 'skills') {
            data = [];

            candidate[manualEntryField].forEach((item) => {
              if (item?.value) {
                data.push({ id: Number(item.value), title: item.label, level: item.level?.value || item.level });
              } else if (null !== item?.title || null !== item) {
                data.push({ id: null, title: item?.title || item, level: item.level?.value || item.level });
              }
            });
          } else if (
            manualEntryField === 'want_learn_skills' ||
            manualEntryField === 'want_work_with_tools'
          ) {
            data = [];

            candidate[manualEntryField].forEach((item) => {
              if (item?.value) {
                data.push({ id: Number(item.value), title: item.label });
              } else if (null !== item?.label || null !== item) {
                data.push({ id: null, title: item?.label || item });
              }
            });
          } else {
            data = [];

            candidate[manualEntryField].forEach((item) => {
              if (item?.id) {
                data.push({ id: Number(item.id), name: item.name });
              } else if (item.name) {
                data.push({ id: null, name: item.name });
              }
            });
          }

          candidateParameters[manualEntryField] = data;
        }
      }

      for (const multipleField of multipleFields) {
        if (candidate[multipleField]?.length > 0) {
          delete candidateParameters[multipleField];

          const data = [];

          candidate[multipleField].forEach((item) => {
            data.push(Number(item.id));
          });

          candidateParameters[multipleField] = data;
        }
      }

      for (const numberField of numberFields) {
        if (
          candidate[numberField] &&
          (candidate[numberField]?.value || candidate[numberField]?.id)
        ) {
          delete candidateParameters[numberField];

          if (numberField === 'salary_rate_currency' || numberField === 'budget_of_biggest_show') {
            candidateParameters[numberField] = Number(candidate[numberField].value);
          } else {
            candidateParameters[`${numberField}_id`] = Number(candidate[numberField].id);
          }
        }
      }

      return candidateParameters;
    },
  },
};
