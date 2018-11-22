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
              {{ role.id ? $t('title.edit') : $t('title.new') }}
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

            <div class="is-post-button-group">
              <a
                @click="create"
                :class="['button is-info', { 'is-loading': creating }]"
                :disabled="disabled"
              >
                {{ role.id ? $t('update') : $t('create') }}
              </a>

              <a
                v-if="role.id"
                @click="showModalConfirm = true"
                :class="['button is-danger is-inverted is-pulled-right', { 'is-loading': deleting }]"
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
      {{ $t('destroy.confirm', { title: $i18n.locale === 'en' ? role.name_en : role.name_lv }) }}
    </modal-confirm>
  </div>
</template>

<script>
  import ModalConfirm from '../../../modals/modal-confirm.vue'
  import ErrorHandler from '../../../../mixins/error-handler'
  import RoleFieldsEn from './role-fields-en.vue'
  import RoleFieldsLv from './role-fields-lv.vue'
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
        role: this.$route.params.role || {},
        creating: false,
        updating: false,
        deleting: false,
        showModalConfirm: false
      }
    },
    created () {
      if (this.role.id === undefined && this.$route.params.id) {
        this.get()
      }
    },
    watch: {
      '$route.params.id' (roleId) {
        if (!roleId) {
          this.role = {}
        }
      }
    },
    computed: {
      fields () {
        let fields = [
          RoleFieldsEn,
          RoleFieldsLv,
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
          .get(`admin/role/${this.$route.params.id}`)
          .then(response => {
            this.disabled = false
            this.role = response.data.data
          })
          .catch(this.handleError)
      },
      create () {
        this.disabled = true

        let method = this.role.id ? 'put' : 'post'
        let route = this.role.id
          ? `admin/role/${this.role.id}`
          : 'admin/role'

        axios[method](route, this.role)
          .then(response => {
            this.$root.notify('success', this.$t(`${method}.success`, { title: this.$i18n.locale === 'en' ? this.role.name_en : this.role.name_lv }))

            this.disabled = false
            this.role = response.data.data

            this.$router.push({
              name: 'admin-role-edit',
              params: {
                id: response.data.data.id
              }
            })
          })
          .catch(this.handleError)
      },
      destroy () {
        this.disabled = this.deleting = true

        axios
          .delete(`admin/role/${this.role.id}`)
          .then(() => {
            this.$router.push({ name: 'admin-roles' })
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
      "title.edit": "Edit role",
      "title.new": "Create new role",
      "create": "Create",
      "destroy": "Delete",
      "destroy.confirm": "Delete \"{title}\"?",
      "destroy.success": "Role successfully deleted",
      "update": "Update",
      "put.success": "Role \"{title}\" successfully updated",
      "post.success": "Role \"{title}\" successfully created"
    },
    "lv": {
      "title.edit": "Labot lomu",
      "title.new": "Izveidot jaunu lomu",
      "create": "Izveidot",
      "destroy": "Dzēst",
      "destroy.confirm": "Dzēst \"{title}\"?",
      "destroy.success": "Loma veiksmīgi izdzēsta",
      "update": "Atjaunot",
      "put.success": "Loma \"{title}\" veiksmīgi atjaunota",
      "post.success": "Loma \"{title}\" veiksmīgi izveidota"
    }
  }
</i18n>
