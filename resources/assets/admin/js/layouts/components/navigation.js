export default {
  computed: {
    navMenuItems() {
      return [
        {
          title: this.$t('admin.navigation.candidates'),
          route: 'candidates.page',
          icon: 'UsersIcon',
          acl(vm) {
            return vm.hasPermissions([
              'candidate.list',
            ]);
          },
        },
        {
          title: this.$t('admin.navigation.cms'),
          route: 'content-data.page',
          icon: 'ToolIcon',
          acl(vm) {
            return vm.hasPermissions([
              'cms.block.list',
            ]);
          },
        },
        {
          title: this.$t('admin.navigation.candidate_settings'),
          icon: 'UsersIcon',
          acl(vm) {
            return vm.hasPermissions([
              'skill.list',
            ]);
          },
          children: [
            {
              title: this.$t('admin.navigation.skills'),
              route: 'skills.page',
              icon: 'FileTextIcon',
              acl(vm) {
                return vm.hasPermissions([
                  'skill.list',
                ]);
              },
            },
            {
              title: this.$t('admin.navigation.timezones'),
              route: 'timezones.page',
              icon: 'ClockIcon',
              acl(vm) {
                return vm.hasPermissions([
                  'timezone.page',
                ]);
              },
            },
          ],
        },
        {
          title: this.$t('admin.navigation.email_template_settings'),
          route: 'email-template-settings.page',
          icon: 'AtSignIcon',
          acl(vm) {
            return vm.hasPermissions([
              'email-template-setting.page',
            ]);
          },
        },
        {
          title: this.$t('admin.navigation.our_partners'),
          route: 'our-partners.page',
          icon: 'UserIcon',
          acl(vm) {
            return vm.hasPermissions([
              'cms.block.our-partners.list',
            ]);
          },
        },
        {
          title: this.$t('admin.navigation.permissions'),
          route: 'roles.page',
          icon: 'SettingsIcon',
          acl(vm) {
            return vm.hasPermissions([
              'role.page',
            ]);
          },
        },
        {
          title: this.$t('admin.navigation.users'),
          route: 'users.page',
          icon: 'UserIcon',
          acl(vm) {
            return vm.hasPermissions([
              'user.page',
            ]);
          },
        },
      ];
    },
  },
};
