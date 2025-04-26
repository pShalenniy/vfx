import isEmpty from 'lodash/isEmpty';
import debounce from 'lodash/debounce';

export default {
  data() {
    return {
      cities: [],
      regions: [],
      selectedCountryId: null,
      selectedRegionId: null,
      citiesTotal: null,
      regionsTotal: null,
      countryValues: Object.freeze(window[window.globalSettingsKey].countries),
    };
  },

  methods: {
    async getLocationOptions(key) {
      if (this[key].country?.id) {
        await this.getRegionsByCountry(this[key].country);
      }

      if (this[key].region?.id) {
        await this.getCitiesByRegion(this[key].region);
      }
    },

    async watchCountryChange(value, oldValue, key) {
      if (value !== oldValue) {
        this[key].region = {};
        this[key].city = {};

        this.citiesTotal = null;
        this.regionsTotal = null;

        if (!isEmpty(value)) {
          await this.getRegionsByCountry(value);
        }
      }
    },

    async watchRegionChange(value, oldValue, key) {
      if (value !== oldValue && !isEmpty(value)) {
        this.selectedRegionId = value.id;

        this[key].city = {};

        this.citiesTotal = null;

        await this.getCitiesList();
      }
    },

    onSearchRegions(keyword, loading) {
      if (!keyword.length) {
        return;
      }

      loading(true);

      this.searchRegions(keyword, loading, this);
    },

    searchRegions: debounce(async (keyword, loading, vm) => {
      try {
        await vm.getRegionsList(keyword, vm);
      } finally {
        loading(false);
      }
    }, 700),

    async getRegionsList(keyword = null, vm = null) {
      vm ??= this;

      if (null === vm.regionsTotal || vm.regionsTotal > 100) {
        try {
          const { data } = await axios.get(
            route('common.region.list.by-country', vm.selectedCountryId),
            { params: { keyword } },
          );

          vm.regions = data.data;
          vm.regionsTotal = data.meta.count;
        } catch (e) {
          vm.$notify.errors(e);
          console.error(e);
        }
      }
    },

    onSearchCities(keyword, loading) {
      if (!keyword.length) {
        return;
      }

      loading(true);

      this.searchCities(keyword, loading, this);
    },

    searchCities: debounce(async (keyword, loading, vm) => {
      try {
        await vm.getCitiesList(keyword, vm);
      } finally {
        loading(false);
      }
    }, 700),

    async getCitiesList(keyword = null, vm = null) {
      vm ??= this;

      if (null === vm.citiesTotal || vm.citiesTotal > 100) {
        try {
          const { data } = await axios.get(
            route('common.city.list.by-region', this.selectedRegionId),
            { params: { keyword } },
          );

          vm.cities = data.data;
          vm.citiesTotal = data.meta.count;
        } catch (e) {
          this.$notify.errors(e);
          console.error(e);
        }
      }
    },

    async getRegionsByCountry(country) {
      this.regions = [];
      this.cities = [];

      this.selectedCountryId = country.id;

      await this.getRegionsList();
    },

    async getCitiesByRegion(region) {
      this.cities = [];

      this.selectedRegionId = region.id;

      await this.getCitiesList();
    },

    prepareUserLocationData(data) {
      const numericFields = [
        'country',
        'region',
        'city',
      ];

      const userData = data;

      for (const numericField of numericFields) {
        if (userData[numericField] && userData[numericField]?.id) {
          userData[`${numericField}_id`] = Number(userData[numericField].id);

          delete userData[numericField];
        }
      }

      return userData;
    },

    getCurrentLocation(city, country, region = null) {
      if (!city && !country && !region) {
        return null;
      }

      const location = [];

      if (country) {
        location.push(country);
      }

      if (region) {
        location.push(region);
      }

      if (city) {
        location.push(city);
      }

      return location.join(', ');
    },
  },
};
