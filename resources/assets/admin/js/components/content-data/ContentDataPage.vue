<template>
  <div id="content-data">
    <app-collapse type="border" class="content-data">
      <app-collapse-item
        v-if="hasAnyPermission([
          'cms.page.home.top.block.1.title',
          'cms.page.home.top.block.1.text',
          'cms.page.home.top.block.1.image',
          'cms.page.home.top.block.2.title',
          'cms.page.home.top.block.2.text',
          'cms.page.home.top.block.2.image',
          'cms.page.home.top.block.3.title',
          'cms.page.home.top.block.3.text',
          'cms.page.home.top.block.3.image',
          'cms.page.home.block.intro.text',
          'cms.page.home.block.intro.expanded.text',
          'cms.page.home.block.about-us',
          'cms.page.home.block.video',
        ])"
        :title="$t('admin.content-data.collapse.home-page.block')"
      >
        <h3>{{ $t('admin.content-data.collapse.home-page.sub_title') }}</h3>
        <b-card
          v-if="hasAnyPermission([
            'cms.page.home.top.block.1.title',
            'cms.page.home.top.block.1.text',
            'cms.page.home.top.block.1.image',
          ])"
          :title="$t('admin.content-data.collapse.home-page.top_block_1_title')"
        >
          <b-form-group v-if="hasPermissions('cms.page.home.top.block.1.title')">
            <label>
              {{ $t('admin.content-data.collapse.home-page.form.title') }}
            </label>
            <b-form-textarea
              v-model="contentData[contentDataKeys.KEY_PAGE_HOME_TOP_BLOCK_1_TITLE]"
              rows="3"
            />
          </b-form-group>
          <b-form-group v-if="hasPermissions('cms.page.home.top.block.1.text')">
            <label>
              {{ $t('admin.content-data.collapse.home-page.form.text') }}
            </label>
            <b-form-textarea
              v-model="contentData[contentDataKeys.KEY_PAGE_HOME_TOP_BLOCK_1_TEXT]"
              rows="3"
            />
          </b-form-group>
          <b-form-group v-if="hasPermissions('cms.page.home.top.block.1.image')">
            <img
              v-if="contentData[contentDataKeys.KEY_PAGE_HOME_TOP_BLOCK_1_IMAGE]"
              class="mb-2"
              alt=""
              :src="contentData[contentDataKeys.KEY_PAGE_HOME_TOP_BLOCK_1_IMAGE]"
            />
            <b-form-file
              :placeholder="$t('admin.content-data.file.placeholder')"
              :drop-placeholder="$t('admin.content-data.file.drop_placeholder')"
              :accept="getAcceptableExtensions('image')"
              @change="onFileChange($event, contentDataKeys.KEY_PAGE_HOME_TOP_BLOCK_1_IMAGE)"
            />
          </b-form-group>
        </b-card>
        <b-card
          v-if="hasAnyPermission([
            'cms.page.home.top.block.2.title',
            'cms.page.home.top.block.2.text',
            'cms.page.home.top.block.2.image.',
          ])"
          :title="$t('admin.content-data.collapse.home-page.top_block_2_title')"
        >
          <b-form-group v-if="hasPermissions('cms.page.home.top.block.2.title')">
            <label>
              {{ $t('admin.content-data.collapse.home-page.form.title') }}
            </label>
            <b-form-textarea
              v-model="contentData[contentDataKeys.KEY_PAGE_HOME_TOP_BLOCK_2_TITLE]"
              rows="3"
            />
          </b-form-group>
          <b-form-group v-if="hasPermissions('cms.page.home.top.block.2.text')">
            <label>
              {{ $t('admin.content-data.collapse.home-page.form.text') }}
            </label>
            <b-form-textarea
              v-model="contentData[contentDataKeys.KEY_PAGE_HOME_TOP_BLOCK_2_TEXT]"
              rows="3"
            />
          </b-form-group>
          <b-form-group v-if="hasPermissions('cms.page.home.top.block.2.image')">
            <img
              v-if="contentData[contentDataKeys.KEY_PAGE_HOME_TOP_BLOCK_2_IMAGE]"
              class="mb-2"
              alt=""
              :src="contentData[contentDataKeys.KEY_PAGE_HOME_TOP_BLOCK_2_IMAGE]"
            />
            <b-form-file
              :placeholder="$t('admin.content-data.file.placeholder')"
              :drop-placeholder="$t('admin.content-data.file.drop_placeholder')"
              :accept="getAcceptableExtensions('image')"
              @change="onFileChange($event, contentDataKeys.KEY_PAGE_HOME_TOP_BLOCK_2_IMAGE)"
            />
          </b-form-group>
        </b-card>
        <b-card
          v-if="hasAnyPermission([
            'cms.page.home.top.block.3.title',
            'cms.page.home.top.block.3.text',
            'cms.page.home.top.block.3.image',
          ])"
          :title="$t('admin.content-data.collapse.home-page.top_block_3_title')"
        >
          <b-form-group v-if="hasPermissions('cms.page.home.top.block.3.title')">
            <label>
              {{ $t('admin.content-data.collapse.home-page.form.title') }}
            </label>
            <b-form-textarea
              v-model="contentData[contentDataKeys.KEY_PAGE_HOME_TOP_BLOCK_3_TITLE]"
              rows="3"
            />
          </b-form-group>
          <b-form-group v-if="hasPermissions('cms.page.home.top.block.3.text')">
            <label>
              {{ $t('admin.content-data.collapse.home-page.form.text') }}
            </label>
            <b-form-textarea
              v-model="contentData[contentDataKeys.KEY_PAGE_HOME_TOP_BLOCK_3_TEXT]"
              rows="3"
            />
          </b-form-group>
          <b-form-group v-if="hasPermissions('cms.page.home.top.block.3.image')">
            <img
              v-if="contentData[contentDataKeys.KEY_PAGE_HOME_TOP_BLOCK_3_IMAGE]"
              class="mb-2"
              alt=""
              :src="contentData[contentDataKeys.KEY_PAGE_HOME_TOP_BLOCK_3_IMAGE]"
            />
            <b-form-file
              :placeholder="$t('admin.content-data.file.placeholder')"
              :drop-placeholder="$t('admin.content-data.file.drop_placeholder')"
              :accept="getAcceptableExtensions('image')"
              @change="onFileChange($event, contentDataKeys.KEY_PAGE_HOME_TOP_BLOCK_3_IMAGE)"
            />
          </b-form-group>
        </b-card>
        <b-card
          v-if="hasPermissions('cms.page.home.block.intro')"
          :title="$t('admin.content-data.collapse.home-page.intro')"
        >
          <b-form-group>
            <label>
              {{ $t('admin.content-data.collapse.home-page.form.text') }}
            </label>
            <b-form-textarea
              v-model="contentData[contentDataKeys.KEY_PAGE_HOME_BLOCK_INTRO_TEXT]"
              rows="3"
            />
          </b-form-group>
          <b-form-group>
            <label>
              {{ $t('admin.content-data.collapse.home-page.form.expanded_text') }}
            </label>
            <b-form-textarea
              v-model="contentData[contentDataKeys.KEY_PAGE_HOME_BLOCK_INTRO_EXPANDED_TEXT]"
              rows="3"
            />
          </b-form-group>
        </b-card>
      </app-collapse-item>

      <app-collapse-item
        v-if="hasAnyPermission([
          'cms.page.subscription.block.intro',
          'cms.page.subscription.thank-you.block.text',
          'cms.page.subscription.inactive.block.text',
        ])"
        :title="$t('admin.content-data.collapse.subscription.block')"
      >
        <b-card
          v-if="hasPermissions('cms.page.subscription.block.intro')"
          :title="$t('admin.content-data.collapse.subscription.subscription_page.title')"
        >
          <b-form-group>
            <label>
              {{ $t('admin.content-data.collapse.subscription.subscription_page.intro') }}
            </label>
            <b-form-textarea
              v-model="contentData[contentDataKeys.KEY_PAGE_SUBSCRIPTION_BLOCK_INTRO]"
              rows="3"
            />
          </b-form-group>
        </b-card>
        <b-card
          v-if="hasPermissions('cms.page.subscription.thank-you.block.text')"
          :title="$t('admin.content-data.collapse.subscription.subscription_thank_you_page.title')"
        >
          <b-form-group>
            <label>
              {{ $t('admin.content-data.collapse.subscription.subscription_thank_you_page.text') }}
            </label>
            <b-form-textarea
              v-model="contentData[contentDataKeys.KEY_PAGE_SUBSCRIPTION_THANK_YOU_BLOCK_TEXT]"
              rows="3"
            />
          </b-form-group>
        </b-card>
        <b-card
          v-if="hasPermissions('cms.page.subscription.inactive.block.text')"
          :title="$t('admin.content-data.collapse.subscription.subscription_inactive_page.title')"
        >
          <b-form-group>
            <label>
              {{ $t('admin.content-data.collapse.subscription.subscription_inactive_page.text') }}
            </label>
            <b-form-textarea
              v-model="contentData[contentDataKeys.KEY_PAGE_SUBSCRIPTION_INACTIVE_BLOCK_TEXT]"
              rows="3"
            />
          </b-form-group>
        </b-card>
      </app-collapse-item>

      <app-collapse-item
        v-if="hasAnyPermission([
          'cms.block.logo',
          'cms.block.social',
          'cms.block.contacts',
        ])"
        :title="$t('admin.content-data.collapse.blocks.title')"
      >
        <b-card
          v-if="hasPermissions('cms.block.logo')"
          :title="$t('admin.content-data.collapse.blocks.logo')"
        >
          <b-form-group>
            <img
              v-if="contentData[contentDataKeys.KEY_BLOCK_LOGO]"
              class="mb-2"
              alt=""
              :src="contentData[contentDataKeys.KEY_BLOCK_LOGO]"
            />
            <b-form-file
              :placeholder="$t('admin.content-data.file.placeholder')"
              :drop-placeholder="$t('admin.content-data.file.drop_placeholder')"
              :accept="getAcceptableExtensions('image')"
              @change="onFileChange($event, contentDataKeys.KEY_BLOCK_LOGO)"
            />
          </b-form-group>
        </b-card>
        <b-card
          v-if="hasPermissions('cms.block.social')"
          :title="$t('admin.content-data.collapse.blocks.social.title')"
        >
          <b-form-group>
            <label>
              {{ $t('admin.content-data.collapse.blocks.social.instagram') }}
            </label>
            <b-form-input v-model="contentData[contentDataKeys.KEY_BLOCK_SOCIAL_INSTAGRAM]" />
          </b-form-group>
          <b-form-group>
            <label>
              {{ $t('admin.content-data.collapse.blocks.social.linkedin') }}
            </label>
            <b-form-input v-model="contentData[contentDataKeys.KEY_BLOCK_SOCIAL_LINKEDIN]" />
          </b-form-group>
          <b-form-group>
            <label>
              {{ $t('admin.content-data.collapse.blocks.social.twitter') }}
            </label>
            <b-form-input v-model="contentData[contentDataKeys.KEY_BLOCK_SOCIAL_TWITTER]" />
          </b-form-group>
        </b-card>
        <b-card
          v-if="hasPermissions('cms.block.contacts')"
          :title="$t('admin.content-data.collapse.blocks.contact.title')"
        >
          <b-form-group>
            <label>
              {{ $t('admin.content-data.collapse.blocks.contact.address_line_1') }}
            </label>
            <b-form-input v-model="contentData[contentDataKeys.KEY_BLOCK_CONTACT_ADDRESS_LINE_1]" />
          </b-form-group>
          <b-form-group>
            <label>
              {{ $t('admin.content-data.collapse.blocks.contact.address_line_2') }}
            </label>
            <b-form-input v-model="contentData[contentDataKeys.KEY_BLOCK_CONTACT_ADDRESS_LINE_2]" />
          </b-form-group>
          <b-form-group>
            <label>
              {{ $t('admin.content-data.collapse.blocks.contact.email') }}
            </label>
            <b-form-input v-model="contentData[contentDataKeys.KEY_BLOCK_CONTACT_EMAIL]" />
          </b-form-group>
          <b-form-group>
            <label>
              {{ $t('admin.content-data.collapse.blocks.contact.phone') }}
            </label>
            <b-form-input v-model="contentData[contentDataKeys.KEY_BLOCK_CONTACT_PHONE]" />
          </b-form-group>
        </b-card>
        <b-card
          v-if="hasAnyPermission([
            'cms.block.tinsel_town.app.store',
            'cms.block.tinsel_town.google.play',
          ])"
          :title="$t('admin.content-data.collapse.blocks.tinsel-town.title')"
        >
          <b-form-group v-if="hasPermissions('cms.block.tinsel_town.app.store')">
            <label>
              {{ $t('admin.content-data.collapse.blocks.tinsel-town.app_store') }}
            </label>
            <b-form-input v-model="contentData[contentDataKeys.KEY_BLOCK_TINSEL_TOWN_APP_STORE]" />
          </b-form-group>
          <b-form-group v-if="hasPermissions('cms.block.tinsel_town.google.play')">
            <label>
              {{ $t('admin.content-data.collapse.blocks.tinsel-town.google_play') }}
            </label>
            <b-form-input
              v-model="contentData[contentDataKeys.KEY_BLOCK_TINSEL_TOWN_GOOGLE_PLAY]"
            />
          </b-form-group>
        </b-card>
      </app-collapse-item>

      <app-collapse-item
        v-if="hasPermissions('cms.page.about-us')"
        :title="$t('admin.content-data.collapse.about-us-page.title')"
      >
        <b-card
          v-if="hasPermissions('cms.page.about-us')"
          :title="$t('admin.content-data.collapse.about-us-page.title')"
        >
          <b-form-group>
            <label>
              {{ $t('admin.content-data.collapse.about-us-page.title') }}
            </label>
            <b-form-input
              v-model="contentData[contentDataKeys.KEY_PAGE_ABOUT_US_TITLE]"
            />
          </b-form-group>
          <b-form-group>
            <label>
              {{ $t('admin.content-data.collapse.about-us-page.text') }}
            </label>
            <quill-editor
              v-model="contentData[contentDataKeys.KEY_PAGE_ABOUT_US_TEXT]"
              :options="editorOptions"
            />
          </b-form-group>
          <b-form-group>
            <label>
              {{ $t('admin.content-data.collapse.about-us-page.the_team_block.title') }}
            </label>
            <b-form-textarea
              v-model="contentData[contentDataKeys.KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_TITLE]"
              rows="3"
            />
          </b-form-group>

          <b-card :title="$t('admin.content-data.collapse.about-us-page.the_team_block.john_story')">
            <b-form-group>
              <label>
                {{ $t('admin.content-data.collapse.about-us-page.the_team_block.full_name') }}
              </label>
              <b-form-input
                v-model="contentData[contentDataKeys.KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_FULL_NAME]"
                type="text"
              />
            </b-form-group>
            <b-form-group>
              <label>
                {{ $t('admin.content-data.collapse.about-us-page.the_team_block.position') }}
              </label>
              <b-form-input
                v-model="contentData[contentDataKeys.KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_POSITION]"
                type="text"
              />
            </b-form-group>
            <b-form-group>
              <img
                v-if="contentData[contentDataKeys.KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_IMAGE]"
                class="mb-2"
                alt=""
                :src="contentData[contentDataKeys.KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_IMAGE]"
              />
              <b-form-file
                :placeholder="$t('admin.content-data.file.placeholder')"
                :drop-placeholder="$t('admin.content-data.file.drop_placeholder')"
                :accept="getAcceptableExtensions('image')"
                @change="onFileChange(
                  $event,
                  contentDataKeys.KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_IMAGE,
                )"
              />
            </b-form-group>
            <b-form-group>
              <label>
                {{ $t('admin.content-data.collapse.about-us-page.the_team_block.text') }}
              </label>
              <b-form-textarea
                v-model="contentData[contentDataKeys.KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_TEXT]"
                rows="3"
              />
            </b-form-group>
            <b-form-group>
              <label>
                {{ $t('admin.content-data.collapse.about-us-page.the_team_block.email') }}
              </label>
              <b-form-input
                v-model="contentData[contentDataKeys.KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_EMAIL]"
              />
            </b-form-group>
            <b-form-group>
              <label>
                {{ $t('admin.content-data.collapse.about-us-page.the_team_block.link_title') }}
              </label>
              <b-form-input
                v-model="contentData[contentDataKeys.KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_LINK_TITLE]"
              />
            </b-form-group>
          </b-card>
          <b-card :title="$t('admin.content-data.collapse.about-us-page.the_team_block.sascha_story')">
            <b-form-group>
              <label>
                {{ $t('admin.content-data.collapse.about-us-page.the_team_block.full_name') }}
              </label>
              <b-form-input
                v-model="contentData[contentDataKeys.KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_FULL_NAME]"
                type="text"
              />
            </b-form-group>
            <b-form-group>
              <label>
                {{ $t('admin.content-data.collapse.about-us-page.the_team_block.position') }}
              </label>
              <b-form-input
                v-model="contentData[contentDataKeys.KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_POSITION]"
                type="text"
              />
            </b-form-group>
            <b-form-group>
              <img
                v-if="contentData[contentDataKeys.KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_IMAGE]"
                class="mb-2"
                alt=""
                :src="contentData[contentDataKeys.KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_IMAGE]"
              />
              <b-form-file
                :placeholder="$t('admin.content-data.file.placeholder')"
                :drop-placeholder="$t('admin.content-data.file.drop_placeholder')"
                :accept="getAcceptableExtensions('image')"
                @change="onFileChange(
                  $event,
                  contentDataKeys.KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_IMAGE,
                )"
              />
            </b-form-group>
            <b-form-group>
              <label>
                {{ $t('admin.content-data.collapse.about-us-page.the_team_block.text') }}
              </label>
              <b-form-textarea
                v-model="contentData[contentDataKeys.KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_TEXT]"
                rows="3"
              />
            </b-form-group>
            <b-form-group>
              <label>
                {{ $t('admin.content-data.collapse.about-us-page.the_team_block.email') }}
              </label>
              <b-form-input
                v-model="contentData[contentDataKeys.KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_EMAIL]"
              />
            </b-form-group>
            <b-form-group>
              <label>
                {{ $t('admin.content-data.collapse.about-us-page.the_team_block.link_title') }}
              </label>
              <b-form-input
                v-model="contentData[contentDataKeys.KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_LINK_TITLE]"
              />
            </b-form-group>
          </b-card>
        </b-card>
      </app-collapse-item>
      <app-collapse-item
        v-if="hasPermissions('cms.page.privacy-policy.text')"
        :title="$t('admin.content-data.collapse.privacy-policy.title')"
      >
        <b-card :title="$t('admin.content-data.collapse.privacy-policy.text')">
          <b-form-group>
            <quill-editor
              v-model="contentData[contentDataKeys.KEY_PAGE_PRIVACY_POLICY_TEXT]"
              :options="editorOptions"
            />
          </b-form-group>
        </b-card>
      </app-collapse-item>

      <app-collapse-item
        v-if="hasAnyPermission([
          'cms.page.terms-and-conditions.text',
          'cms.page.terms-and-conditions.disclaimer',
        ])"
        :title="$t('admin.content-data.collapse.terms_and_conditions.title')"
      >
        <b-card :title="$t('admin.content-data.collapse.terms_and_conditions.text')">
          <b-form-group>
            <quill-editor
              v-model="contentData[contentDataKeys.KEY_PAGE_TERMS_AND_CONDITIONS_TEXT]"
              :options="editorOptions"
            />
          </b-form-group>
        </b-card>

        <b-card :title="$t('admin.content-data.collapse.terms_and_conditions.disclaimer')">
          <b-form-group>
            <quill-editor
              v-model="contentData[contentDataKeys.KEY_PAGE_TERMS_AND_CONDITIONS_DISCLAIMER]"
              :options="editorOptions"
            />
          </b-form-group>
        </b-card>
      </app-collapse-item>

      <app-collapse-item
        v-if="hasPermissions('cms.page.active-sessions.test')"
        :title="$t('admin.content-data.collapse.active-sessions.title')"
      >
        <b-card :title="$t('admin.content-data.collapse.active-sessions.text')">
          <b-form-group>
            <b-form-input
              v-model="contentData[contentDataKeys.KEY_PAGE_ACTIVE_SESSIONS_TEXT]"
            />
          </b-form-group>
        </b-card>
      </app-collapse-item>

      <app-collapse-item
        v-if="hasPermissions('cms.page.contact-us.disclaimer')"
        :title="$t('admin.content-data.collapse.contact-us-page.title')"
      >
        <b-card :title="$t('admin.content-data.collapse.contact-us-page.email_text')">
          <b-form-group>
            <b-form-input
              v-model="contentData[contentDataKeys.KEY_PAGE_CONTACT_US_DISCLAIMER_EMAIL_TEXT]"
            />
          </b-form-group>
        </b-card>

        <b-card :title="$t('admin.content-data.collapse.contact-us-page.email')">
          <b-form-group>
            <b-form-input
              v-model="contentData[contentDataKeys.KEY_PAGE_CONTACT_US_DISCLAIMER_EMAIL]"
            />
          </b-form-group>
        </b-card>

        <b-card :title="$t('admin.content-data.collapse.contact-us-page.disclaimer')">
          <b-form-group>
            <b-form-input
              v-model="contentData[contentDataKeys.KEY_PAGE_CONTACT_US_DISCLAIMER]"
            />
          </b-form-group>
        </b-card>
      </app-collapse-item>
    </app-collapse>

    <b-card no-body class="mt-1">
      <b-card-body>
        <b-button
          class="float-right"
          variant="primary"
          @click.prevent="set"
        >
          {{ $t('admin.content-data.save') }}
        </b-button>
      </b-card-body>
    </b-card>
  </div>
</template>

<script>
import jsonToFormData from 'json-form-data';
import { quillEditor } from 'vue-quill-editor';
import hasFileInput from '@common/js/mixins/hasFileInput';
import contentData from '@common/js/constants/contentData';

export default {
  components: {
    quillEditor,
  },

  mixins: [
    hasFileInput,
  ],

  data() {
    return {
      contentData: {},
      contentDataKeys: Object.freeze(contentData),
      editorOptions: {
        theme: 'snow',
      },
    };
  },

  created() {
    this.getContentData();
  },

  methods: {
    async getContentData() {
      this.$overlay.show();

      await this.getContentDataWithoutOverlay();

      this.$overlay.hide();
    },

    async getContentDataWithoutOverlay() {
      try {
        const { data } = await axios.get(route('admin.content-data.list'));

        this.contentData = data.data;
      } catch (e) {
        console.error(e);
        this.$notify.errors(e);
      }
    },

    async set() {
      this.$overlay.show();

      const data = jsonToFormData(Object.assign(
        {},
        this.contentData,
        { _method: 'PATCH' },
      ));

      try {
        await axios.post(route('admin.content-data.set'),
          data,
          {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          },
        );

        this.$notify.success(this.$t('admin.content-data.messages.success'));
      } catch (e) {
        console.error(e);
        this.$notify.errors(e);
      } finally {
        await this.getContentDataWithoutOverlay();

        this.$overlay.hide();
      }
    },

    onFileChange($event, key) {
      this.contentData[key] = $event.target.files[0];
    },
  },
};
</script>
