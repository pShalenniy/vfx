import ToastificationContent from '@common/js/vendor/ToastificationContent';

export default (vm) => {
  if (!vm.$notify) {
    const $notify = {
      success(title, icon = 'CheckCircleIcon') {
        vm.$toast({
          component: ToastificationContent,
          props: {
            title,
            icon,
            variant: 'blue',
          },
        });
      },
      error(title, icon = 'XCircleIcon') {
        vm.$toast({
          component: ToastificationContent,
          props: {
            title,
            icon,
            variant: 'danger',
          },
        });
      },
      errors(data) {
        if (data.response) {
          data = data.response.data;

          if (data.errors) {
            Object.keys(data.errors).forEach((key) => {
              data.errors[key].forEach((error) => {
                this.error(error);
              });
            });
          } else if (data.message) {
            this.error(data.message);
          }
        } else {
          this.error(String(data));
        }
      },
    };

    vm.$notify = $notify;
    vm.prototype.$notify = $notify;
  }
};
