import isObject from 'lodash/isObject';
import i18n from '@common/js/i18n';

export default (vm) => {
  if (!vm.$confirm) {
    const $confirm = {
      delete(data) {
        data = isObject(data) ? data : { text: data };

        return vm.swal({
          title: data.title || i18n.t('common.confirmation.delete.are_you_sure'),
          text: data.text || i18n.t('common.confirmation.delete.you_wont_revert'),
          icon: data.icon || 'warning',
          showCancelButton: true,
          confirmButtonText: data.confirmButtonText || i18n.t('common.confirmation.delete.yes_delete'),
          cancelButtonText: data.cancelButtonText || i18n.t('common.confirmation.cancel'),
          confirmButtonColor: data.confirmButtonColor,
          cancelButtonColor: data.cancelButtonColor,
          customClass: data.customClass || {
            confirmButton: 'btn btn-danger',
            cancelButton: 'btn btn-outline-secondary ml-1',
          },
        });
      },

      confirm(data) {
        return vm.swal({
          title: data.title || i18n.t('common.confirmation.delete.are_you_sure'),
          text: data.text,
          icon: data.icon || 'warning',
          showCancelButton: true,
          allowOutsideClick: false,
          denyButtonText: i18n.t('common.confirmation.confirm.buttons.deny'),
          confirmButtonText: i18n.t('common.confirmation.confirm.buttons.confirm'),
          confirmButtonColor: data.confirmButtonColor || 'btn btn-danger',
          cancelButtonColor: data.cancelButtonColor || 'btn btn-outline-secondary ml-1',
          customClass: data.customClass || {
            confirmButton: 'btn btn-danger',
            cancelButton: 'btn btn-outline-secondary ml-1',
          },
        });
      },
    };

    vm.$confirm = $confirm;
    vm.prototype.$confirm = $confirm;
  }
};
