function deepFreeze(values) {
  Object.keys(values).forEach((key) => {
    if (
      values[key] !== null &&
      (typeof values[key] === 'object') &&
      !Object.isFrozen(values[key])
    ) {
      deepFreeze(values[key]);
    }
  });

  return Object.freeze(values);
}

module.exports = deepFreeze;
