export default {
  methods: {
    getLinkedinWorkingPeriod(workingPeriod) {
      if (null === workingPeriod?.years && null === workingPeriod?.months) {
        return null;
      }

      let years = null;
      let months = null;

      if (workingPeriod?.years) {
        years = this.$tc('client.candidate.years', workingPeriod.years);
      }

      if (workingPeriod?.months) {
        months = this.$tc('client.candidate.months', workingPeriod.months);
      }

      if (null !== years && null === months) {
        return years;
      }

      if (null !== months && null === years) {
        return months;
      }

      return `${years} ${months}`;
    },
  },
};
