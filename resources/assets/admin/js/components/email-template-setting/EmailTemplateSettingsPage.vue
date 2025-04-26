<template>
  <div id="email-template-settings-page">
    <b-card v-for="emailTemplate in emailTemplates" :key="emailTemplate.key" class="mb-2">
      <app-collapse>
        <app-collapse-item :title="collapseTitle(emailTemplate.key)">
          <b-form-group>
            <label>{{ $t('admin.email-template-settings.form.subject') }}</label>
            <b-form-input
              v-model="emailTemplate.subject"
              :placeholder="$t('admin.email-template-settings.form.subject')"
            />
          </b-form-group>
          <b-form-group>
            <label>{{ $t('admin.email-template-settings.form.emails') }}</label>
            <b-form-tags
              v-model="emailTemplate.emails"
              :placeholder="$t('admin.email-template-settings.form.emails')"
            />
          </b-form-group>
          <b-form-group>
            <label>{{ $t('admin.email-template-settings.form.body') }}</label>
            <quill-editor
              v-model="emailTemplate.body"
              :options="editorOptions"
            />

            <div v-if="getTokens(emailTemplate.key).length" class="mt-1 text-secondary">
              <h5>{{ $t('admin.email-template-settings.available_tokens') }}</h5>
              <ul class="list-unstyled">
                <li
                  v-for="token in getTokens(emailTemplate.key)"
                  :key="tokens.id"
                  v-html="token"
                />
              </ul>
            </div>
          </b-form-group>
          <b-button class="float-right" variant="primary" @click="save(emailTemplate)">
            {{ $t('admin.email-template-settings.form.save') }}
          </b-button>
        </app-collapse-item>
      </app-collapse>
    </b-card>
  </div>
</template>

<script>
import { quillEditor } from 'vue-quill-editor';

export default {
  components: {
    quillEditor,
  },

  data() {
    return {
      tokens: [],
      emailTemplates: [],
      editorOptions: {
        theme: 'snow',
      },
    };
  },

  computed: {
    collapseTitle() {
      return (key) => {
        const accordions = this.$t('admin.email-template-settings.accordion');

        return accordions[key] || '';
      };
    },
    getTokens() {
      return (key) => {
        this.tokens = this.$t('admin.email-template-settings.tokens');

        if (key in this.tokens) {
          const keys = Object.keys(this.tokens[key]);

          return keys.map((token) => {
            return `<code>[${token}]</code>: ${this.tokens[key][token]}`;
          });
        }

        return [];
      };
    },
  },

  created() {
    this.getEmailTemplates();
  },

  methods: {
    async getEmailTemplates() {
      this.$overlay.show();

      try {
        const { data } = await axios.get(route('admin.email-template-setting.list'));

        this.emailTemplates = data.data;
      } catch (e) {
        this.$notify.errors(e);
      } finally {
        this.$overlay.hide();
      }
    },
    async save(emailTemplate) {
      this.$overlay.show();

      try {
        await axios.patch(route('admin.email-template-setting.update', emailTemplate), emailTemplate);

        this.$notify.success(this.$t('admin.email-template-settings.notification.success'));
      } catch (e) {
        this.$notify.errors(e);
      } finally {
        this.$overlay.hide();
      }
    },
  },
};
</script>

