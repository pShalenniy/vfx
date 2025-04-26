import Vue from 'vue';
import VueRouter from 'vue-router';

import CandidatesPage from '@admin/js/components/candidate/CandidatesPage';
import ContentDataPage from '@admin/js/components/content-data/ContentDataPage';
import EmailTemplateSettingsPage from '@admin/js/components/email-template-setting/EmailTemplateSettingsPage';
import OurPartnersPage from '@admin/js/components/our-partner/OurPartnersPage';
import RolesPage from '@admin/js/components/role/RolesPage';
import SkillsPage from '@admin/js/components/skill/SkillsPage';
import TimezonePage from '@admin/js/components/timezone/TimezonePage';
import UsersPage from '@admin/js/components/user/UsersPage';

Vue.use(VueRouter);

export default new VueRouter({
  mode: 'history',
  routes: [
    {
      name: 'candidates.page',
      path: '/admin/candidates',
      alias: '/admin',
      component: CandidatesPage,
      meta: {
        guest: false,
        layout: 'default',
      },
    },
    {
      name: 'content-data.page',
      path: '/admin/content-data',
      component: ContentDataPage,
      meta: {
        guest: false,
        layout: 'default',
      },
    },
    {
      name: 'email-template-settings.page',
      path: '/admin/email-template-settings',
      component: EmailTemplateSettingsPage,
      meta: {
        guest: false,
        layout: 'default',
      },
    },
    {
      name: 'our-partners.page',
      path: '/admin/our-partners',
      component: OurPartnersPage,
      meta: {
        guest: false,
        layout: 'default',
      },
    },
    {
      name: 'roles.page',
      path: '/admin/roles',
      component: RolesPage,
      meta: {
        guest: false,
        layout: 'default',
      },
    },
    {
      name: 'skills.page',
      path: '/admin/skills',
      component: SkillsPage,
      meta: {
        guest: false,
        layout: 'default',
      },
    },
    {
      name: 'timezones.page',
      path: '/admin/timezones',
      component: TimezonePage,
      meta: {
        guest: false,
        layout: 'default',
      },
    },
    {
      name: 'users.page',
      path: '/admin/users',
      component: UsersPage,
      meta: {
        guest: false,
        layout: 'default',
      },
    },
  ],
});
