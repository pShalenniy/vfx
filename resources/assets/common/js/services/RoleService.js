import isString from 'lodash/isString';
import { useUserStore } from '@common/js/store/user';

class RoleService {
  hasPermissions(permissions) {
    if (permissions === null) {
      return false;
    }

    const userStore = useUserStore();
    const permissionList = isString(permissions) ? permissions.split('|') : permissions;

    if (!permissionList.length) {
      return true;
    }

    for (const permission of permissionList) {
      if (!userStore.permissions[permission]) {
        return false;
      }
    }

    return true;
  }

  hasAnyPermission(permissions) {
    if (permissions === null) {
      return false;
    }

    const userStore = useUserStore();
    const permissionList = isString(permissions) ? permissions.split('|') : permissions;

    if (!permissionList.length) {
      return true;
    }

    for (const permission of permissionList) {
      if (userStore.permissions[permission]) {
        return true;
      }
    }

    return false;
  }
}

export default new RoleService();
