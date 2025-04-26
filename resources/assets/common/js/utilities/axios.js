import axios from 'axios';
import Qs from 'qs';
import isObject from 'lodash/isObject';
import storageConstants from '@common/js/constants/storageConstants';
import storage from '@common/js/utilities/storage';
import  { useUserStore } from '@common/js/store/user';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.withCredentials = true;

// todo: add recursive traversal of objects
axios.interceptors.request.use((config) => {
  config.paramsSerializer = {
    serialize: (params) => {
      Object.entries(params).forEach(([key, value]) => {
        if (isObject(value)) {
          Object.entries(value).forEach(([field, fieldsValue]) => {
            if (typeof (fieldsValue) === 'boolean') {
              params[key][field] = +fieldsValue;
            }
          });
        } else if (typeof (value) === 'boolean') {
          params[key] = +value;
        }
      });

      return Qs.stringify(params, {
        arrayFormat: 'indices',
        encode: false,
        skipNulls: true,
      });
    },
  };

  return config;
});

axios.interceptors.response.use(
  async (response) => response,
  async (error) => {
    if (error.response && error.response.status === 401) {
      storage.remove(storageConstants.API_TOKEN);

      const userStore = useUserStore();

      userStore.deleteUser();

      window.location.href = route('login.view');
    }

    return Promise.reject(error);
  },
);

export default axios;
