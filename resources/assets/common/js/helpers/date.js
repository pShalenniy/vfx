import isObject from 'lodash/isObject';

function getFormattedDate(date, delimiter = '') {
  return date < 10 ? `0${date}${delimiter}` : `${date}${delimiter}`;
}

function parseDate(value) {
  let date = new Date(value);

  if (isNaN(date.getDate())) {
    const valueParts = value.replace(/[-:]/g, ' ').split(' ');

    if (valueParts.length === 6) {
      const [year, month, day, hours, minutes, seconds] = valueParts;
      date = new Date(year, month - 1, day, hours, minutes, seconds);
    }
  }

  return date;
}

export function getDateInUKFormat(value) {
  if (!value) {
    return '';
  }

  if (isObject(value) && value.date) {
    value = value.date;
  }

  const date = parseDate(value);

  const day = date.getDate();
  const month = date.getMonth() + 1;

  let formatted = getFormattedDate(day, '/');
  formatted += getFormattedDate(month);
  formatted += `/${date.getFullYear()}`;

  return formatted;
}

export function getDateInLocalFormat(value, timeValue = false) {
  if (!value) {
    return '';
  }

  if (isObject(value) && value.date) {
    value = value.date;
  }

  let date = new Date(value);

  if (isNaN(date.getDate())) {
    const valueParts = value.replace(/[-:]/g, ' ').split(' ');

    if (valueParts.length === 6) {
      const [year, month, day, hours, minutes, seconds] = valueParts;
      date = new Date.UTC(year, month - 1, day, hours, minutes, seconds);
    }
  }

  if (timeValue) {
    const locale = navigator.language ?? 'en-GB';

    return new Intl.DateTimeFormat(locale, { dateStyle: 'short', timeStyle: 'medium' }).format(date);
  }

  return new Intl.DateTimeFormat().format(date);
}
