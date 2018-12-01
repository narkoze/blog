<template>
  <div>
    <transition
      name="fade"
      appear
    >
      <div class="card">
        <div class="card-content">
          <h1 class="title">
            {{ post.id ? $t('title.edit') : $t('title.new') }}
            <spinner v-if="disabled"></spinner>
          </h1>

          <div class="columns">
            <div
              v-for="fields in fields"
              :key="fields.name"
              :is="fields"
              class="column is-paddingless-bottom"
            >
            </div>
          </div>

          <multiselect-tags
            :selected="post.tags"
            @selected="tags => post.tags = tags"
          >
          </multiselect-tags>

          <div class="is-button-group">
            <a
              @click="publish()"
              :class="['button is-info', { 'is-loading': publishing }]"
              :disabled="disabled"
            >
              {{ post.published_at ? $t('update') : $t('publish') }}
            </a>

            <a
              @click="publish(true)"
              :class="['button', { 'is-loading': saving }]"
              :disabled="disabled"
            >
              {{ $t('save') }}
            </a>

            <router-link
              v-if="post.id"
              :to="{
                name: 'post',
                params: {
                  id: post.id,
                  post: post
                }
              }"
              class="button"
              :disabled="disabled"
            >
              {{ post.published_at ? $t('view') : $t('preview') }}
            </router-link>

            <a
              v-if="post.id"
              @click="showModalConfirm = true"
              :class="['button is-danger is-inverted is-pulled-right', { 'is-loading': destroying }]"
              :disabled="disabled"
            >
              {{ $t('destroy') }}
            </a>
          </div>
        </div>
      </div>
    </transition>

    <modal-image
      v-if="showImages"
      @close="showImages = false"
      @selected="images => {
        showImages = false
        insertImages(images)
      }"
    >
    </modal-image>

    <modal-confirm
      v-if="showModalConfirm"
      @confirmed="() => {
        showModalConfirm = false
        destroy()
      }"
      @close="showModalConfirm = false"
    >
      {{ $t('destroy.confirm', { title: $i18n.locale === 'en' ? post.title_en : post.title_lv }) }}
    </modal-confirm>
  </div>
</template>

<script>
  import MultiselectTags from '../../../multiselects/multiselect-tags.vue'
  import ModalConfirm from '../../../modals/modal-confirm.vue'
  import ErrorHandler from '../../../../mixins/error-handler'
  import ModalImage from '../../../modals/modal-image.vue'
  import PostFieldsEn from './post-fields-en.vue'
  import PostFieldsLv from './post-fields-lv.vue'
  import Spinner from '../../../spinner.vue'
  import tinymce from 'tinymce/tinymce'
  import tinymceLocale from '../../../../tinymce-lv.js'
  import 'tinymce/themes/modern/theme'
  import 'tinymce/plugins/colorpicker'
  import 'tinymce/plugins/textcolor'
  import 'tinymce/plugins/pagebreak'
  import 'tinymce/plugins/lists'
  import 'tinymce/plugins/table'
  import 'tinymce/plugins/paste'
  import 'tinymce/plugins/link'
  import 'tinymce/plugins/hr'
  import axios from 'axios'

  export default {
    components: {
      MultiselectTags,
      ModalConfirm,
      ModalImage,
      Spinner
    },
    mixins: [
      ErrorHandler,
    ],
    data: function () {
      return {
        post: this.$route.params.post || {
          tags: []
        },
        publishing: false,
        destroying: false,
        saving: false,
        showModalConfirm: false,
        content_en_initialized: false,
        content_lv_initialized: false,
        showImages: false
      }
    },
    mounted () {
      this.$events.$on('locale-changed', () => {
        tinymce.remove()
        this.$nextTick(this.initTinymce)
      })

      if (this.post.id === undefined && this.$route.params.id) {
        this.get()
      } else {
        this.initTinymce()
      }
    },
    watch: {
      '$route.params.id' (postId) {
        if (!postId) {
          this.post = {
            tags: []
          }
          tinymce.remove()
          this.$nextTick(this.initTinymce)
        }
      }
    },
    computed: {
      fields () {
        let fields = [
          PostFieldsEn,
          PostFieldsLv,
        ]

        return this.$root.$i18n.locale === 'lv'
          ? fields.reverse()
          : fields
      }
    },
    methods: {
      initTinymce () {
        tinymce.init({
          selector: '#content_en, #content_lv',
          language: this.$root.$i18n.locale,
          language_url: tinymceLocale,
          skin_url: '/css/tinymce/skins/lightgray',
          plugins: 'lists textcolor colorpicker pagebreak link table hr paste',
          toolbar: 'styleselect | bold italic underline strikethrough superscript subscript forecolor removeformat | bullist numlist | outdent indent | alignleft aligncenter alignright alignjustify | image link blockquote table hr pagebreak | undo redo',
          style_formats: [
            { title: this.$t('paragraph'), block: 'p' },
            { title: this.$t('title1'), block: 'h1', classes: 'title is-1' },
            { title: this.$t('title2'), block: 'h2', classes: 'title is-2' },
            { title: this.$t('title3'), block: 'h3', classes: 'title is-3' },
            { title: this.$t('title4'), block: 'h4', classes: 'title is-4' },
            { title: this.$t('title5'), block: 'h5', classes: 'title is-5' },
            { title: this.$t('title6'), block: 'h6', classes: 'title is-6' },
          ],
          table_default_attributes: {
            class: 'table is-striped is-narrow is-hoverable is-fullwidth'
          },
          pagebreak_split_block: true,
          pagebreak_separator: '<!-- pagebreak -->',
          content_css: '/css/tinymce.css',
          height: '50vh',
          menubar: false,
          branding: false,
          paste_as_text: true,
          setup: editor => {
            editor.on('init', () => {
              switch (editor.id) {
                case 'content_en': this.content_en_initialized = true; break
                case 'content_lv': this.content_lv_initialized = true; break
              }
            })
            editor.addButton('image', {
              title: this.$t('addimages'),
              icon: 'image',
              onclick: () => {
                this.showImages = true
                editor.focus()
              }
            })
          },
          init_instance_callback: editor => {
            editor.on('KeyUp', () => { this.getContent(editor) })
            editor.on('Change', () => { this.getContent(editor) })
            editor.on('focus', () => { document.querySelector(`#${editor.editorContainer.getAttribute('id')}`).classList.add('is-active') })
            editor.on('blur', () => { document.querySelector(`#${editor.editorContainer.getAttribute('id')}`).classList.remove('is-active') })
          }
        })
      },
      getContent (editor) {
        switch (editor.id) {
          case 'content_en': this.post.content_en = editor.getContent(); break
          case 'content_lv': this.post.content_lv = editor.getContent(); break
        }
      },
      switchEditorsMode (mode) {
        tinymce.editors.forEach(editor => {
          editor.setMode(mode)
        })
      },
      get () {
        this.disabled = true

        axios
          .get(`admin/post/${this.$route.params.id}`)
          .then(response => {
            this.disabled = false
            this.post = response.data.data
            this.$nextTick(this.initTinymce)
          })
          .catch(this.handleError)
      },
      publish (save = false) {
        save ? this.saving = true : this.publishing = true
        this.disabled = true
        this.errors = {}
        this.switchEditorsMode('readonly')

        let method = this.post.id ? 'put' : 'post'
        let route = this.post.id
          ? `admin/post/${this.post.id}`
          : 'admin/post'

        console.log(this.post.content_lv)

        axios[method](route, {
          ...this.post,
          save
        })
          .then(response => {
            let success = 'update.success'
            if (this.saving) success = 'save.success'
            if (this.publishing && !this.post.published_at) success = 'publish.success'
            this.$root.notify('success', this.$t(success, { title: this.$i18n.locale === 'en' ? this.post.title_en : this.post.title_lv }))

            this.disabled = this.publishing = this.saving = false
            this.switchEditorsMode('design')
            this.post = response.data.data

            this.$router.push({
              name: 'admin-post-edit',
              params: {
                id: response.data.data.id
              }
            })
          })
          .catch(error => {
            this.publishing = this.saving = false
            this.handleError(error)
            this.switchEditorsMode('design')
          })
      },
      destroy () {
        this.disabled = this.destroying = true

        axios
          .delete(`admin/post/${this.post.id}`)
          .then(() => {
            this.$router.push({ name: 'admin-posts' })
            this.$root.notify('success', this.$t('destroy.success'))
          })
          .catch(this.handleError)
      },
      insertImages (images) {
        images.forEach(image => {
          tinymce.activeEditor.insertContent(`
            <img
              class="image"
              width="${image.width > 365 ? 365 : image.width}"
              src="${image.images.medium}"
              data-title="${image.name}"
              data-original-src="${image.images.original}"
              data-width="${image.width}"
              data-height="${image.height}"
            >
          `)
        })
      }
    },
    beforeDestroy () {
      tinymce.remove()
      this.$events.$off('locale-changed')
    }
  }
</script>

<style lang="scss">
  .mce-tinymce.mce-container.mce-panel {
    border-color: #dbdbdb;

    &.is-active {
      border-color: #3273dc;

      $box-shadow: 0 0 0 0.125em rgba(50, 115, 220, 0.25);
      -webkit-box-shadow: $box-shadow;
         -moz-box-shadow: $box-shadow;
          -ms-box-shadow: $box-shadow;
           -o-box-shadow: $box-shadow;
              box-shadow: $box-shadow;
    }
  }
  .children-error > .mce-tinymce.mce-container.mce-panel.is-active {
      $box-shadow: 0 0 0 0.125em rgba(255, 56, 96, 0.25);
      -webkit-box-shadow: $box-shadow;
         -moz-box-shadow: $box-shadow;
          -ms-box-shadow: $box-shadow;
           -o-box-shadow: $box-shadow;
              box-shadow: $box-shadow;
  }
  .mce-tinymce.mce-container.mce-panel:hover:not(.is-active) {
    border-color: #b5b5b5;
  }
  .children-error > .mce-tinymce.mce-container.mce-panel {
    border-color: #ff3860 !important;
  }
</style>

<i18n>
  {
    "en": {
      "destroy.confirm": "Delete \"{title}\"?",
      "destroy.success": "Post successfully deleted",
      "destroy": "Delete",
      "paragraph": "Paragraph",
      "preview": "Preview",
      "publish.success": "Post \"{title}\" successfully published",
      "publish": "Publish",
      "save.success": "Post \"{title}\" successfully saved as draft",
      "save": "Save as draft",
      "title.edit": "Edit post",
      "title.new": "Add new post",
      "title1": "Heading 1",
      "title2": "Heading 2",
      "title3": "Heading 3",
      "title4": "Heading 4",
      "title5": "Heading 5",
      "title6": "Heading 6",
      "update.success": "Post \"{title}\" successfully updated",
      "update": "Update",
      "view": "View",
      "addimages": "Add images"
    },
    "lv": {
      "destroy.confirm": "Dzēst \"{title}\"?",
      "destroy.success": "Ziņa veiksmīgi izdzēsta",
      "destroy": "Dzēst",
      "paragraph": "Rindkopa",
      "preview": "Priekšskatīt",
      "publish.success": "Ziņa \"{title}\" veiksmīgi publicēta",
      "publish": "Publicēt",
      "save.success": "Ziņa \"{title}\" veiksmīgi saglabāta kā melnraksts",
      "save": "Saglabāt melnrakstu",
      "title.edit": "Labot ziņu",
      "title.new": "Pievienot jaunu ziņu",
      "title1": "1. līmeņa virsraksts",
      "title2": "2. līmeņa virsraksts",
      "title3": "3. līmeņa virsraksts",
      "title4": "4. līmeņa virsraksts",
      "title5": "5. līmeņa virsraksts",
      "title6": "6. līmeņa virsraksts",
      "update.success": "Ziņa \"{title}\" veiksmīgi atjaunota",
      "update": "Atjaunot",
      "view": "Skatīt",
      "addimages": "Pievienot attēlus"
    }
  }
</i18n>
