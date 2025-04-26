import isArray from 'lodash/isArray';
import isObject from 'lodash/isObject';
import isBoolean from 'lodash/isBoolean';

export default {
  clearObject(object) {
    if (isArray(object)) {
      for (const key in object) {
        if (isObject(object[key])) {
          object[key] = this.clearObject(object[key]);
        } else {
          object[key] = null;
        }
      }

      return object.filter((value) => value !== undefined);
    } else if (isObject(object)) {
      Object.keys(object).forEach((key) => {
        const value = this.clearObject(object[key]);

        if (isArray(value)) {
          object[key] = this.clearObject(value);
        } else {
          object[key] = value;
        }
      });

      return object;
    } else if (isBoolean(object)) {
      return false;
    }

    return null;
  },
};
