import RoleService from '@common/js/services/RoleService';

export default {
  methods: {
    hasPermissions(permissions) {
      return RoleService.hasPermissions(permissions);
    },
    hasAnyPermission(permissions) {
      return RoleService.hasAnyPermission(permissions);
    },
  },
};
