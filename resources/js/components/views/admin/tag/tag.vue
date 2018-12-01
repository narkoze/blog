<template>
  <div>
    <transition
      name="fade"
      appear
    >
      <div class="card">
        <div class="card-content">
          <div class="content">
            <h1 class="title">
              {{ tag.id ? $t('title.edit') : $t('title.new') }}
              <spinner v-if="disabled"></spinner>
            </h1>

            <div class="columns">
              <div
                v-for="fields in fields"
                :key="fields.name"
                :is="fields"
                class="column"
              >
              </div>
            </div>

            <div class="is-button-group">
              <a
                @click="create"
                :class="['button is-info', { 'is-loading': creating }]"
                :disabled="disabled"
              >
                {{ tag.id ? $t('update') : $t('create') }}
              </a>

              <a
                v-if="tag.id"
                @click="showModalConfirm = true"
                :class="['button is-danger is-inverted is-pulled-right', { 'is-loading': destroying }]"
                :disabled="disabled"
              >
                {{ $t('destroy') }}
              </a>
            </div>
          </div>
        </div>
      </div>
    </transition>

    <modal-confirm
      v-if="showModalConfirm"
      @confirmed="() => {
        showModalConfirm = false
        destroy()
      }"
      @close="showModalConfirm = false"
    >
      {{ $t('destroy.confirm', { title: $i18n.locale === 'en' ? tag.name_en : tag.name_lv }) }}
    </modal-confirm>
  </div>
</template>

<script>
  import ModalConfirm from '../../../modals/modal-confirm.vue'
  import ErrorHandler from '../../../../mixins/error-handler'
  import TagFieldsEn from './tag-fields-en.vue'
  import TagFieldsLv from './tag-fields-lv.vue'
  import Spinner from '../../../spinner.vue'
  import axios from 'axios'

  export default {
    components: {
      ModalConfirm,
      Spinner
    },
    mixins: [
      ErrorHandler,
    ],
    data: function () {
      return {
        tag: this.$route.params.tag || {},
        creating: false,
        destroying: false,
        showModalConfirm: false
      }
    },
    beforeRouteEnter (to, from, next) {
      next(vm => {
        if (Object.keys(to.query).length) {
          vm.tag = {
            name_en: to.query.tag,
            name_lv: to.query.tag
          }
        }
      })
    },
    created () {
      if (this.tag.id === undefined && this.$route.params.id) {
        this.get()
      }
    },
    watch: {
      '$route.params.id' (tagId) {
        if (!tagId) {
          this.tag = {}
        }
      }
    },
    computed: {
      fields () {
        let fields = [
          TagFieldsEn,
          TagFieldsLv,
        ]

        return this.$root.$i18n.locale === 'lv'
          ? fields.reverse()
          : fields
      }
    },
    methods: {
      get () {
        this.disabled = true

        axios
          .get(`admin/tag/${this.$route.params.id}`)
          .then(response => {
            this.disabled = false
            this.tag = response.data.data
          })
          .catch(this.handleError)
      },
      create () {
        this.disabled = true
        this.errors = {}

        let method = this.tag.id ? 'put' : 'post'
        let route = this.tag.id
          ? `admin/tag/${this.tag.id}`
          : 'admin/tag'

        axios[method](route, this.tag)
          .then(response => {
            this.$root.notify('success', this.$t(`${method}.success`, { title: this.$i18n.locale === 'en' ? this.tag.name_en : this.tag.name_lv }))

            this.disabled = false
            this.tag = response.data.data

            this.$router.push({
              name: 'admin-tag-edit',
              params: {
                id: response.data.data.id
              }
            })
          })
          .catch(this.handleError)
      },
      destroy () {
        this.disabled = this.destroying = true

        axios
          .delete(`admin/tag/${this.tag.id}`)
          .then(() => {
            this.$router.push({ name: 'admin-tags' })
            this.$root.notify('success', this.$t('destroy.success'))
          })
          .catch(this.handleError)
      }
    },
    beforeDestroy () {
      this.$events.$off('locale-changed')
    }
  }
</script>

<i18n>
  {
    "en": {
      "title.edit": "Edit tag",
      "title.new": "Create new tag",
      "create": "Create",
      "destroy": "Delete",
      "destroy.confirm": "Delete \"{title}\"?",
      "destroy.success": "Tag successfully deleted",
      "update": "Update",
      "put.success": "Tag \"{title}\" successfully updated",
      "post.success": "Tag \"{title}\" successfully created"
    },
    "lv": {
      "title.edit": "Labot tēmturi",
      "title.new": "Izveidot jaunu tēmturi",
      "create": "Izveidot",
      "destroy": "Dzēst",
      "destroy.confirm": "Dzēst \"{title}\"?",
      "destroy.success": "Tēmturis veiksmīgi izdzēsta",
      "update": "Atjaunot",
      "put.success": "Tēmturis \"{title}\" veiksmīgi atjaunota",
      "post.success": "Tēmturis \"{title}\" veiksmīgi izveidota"
    }
  }
</i18n>
