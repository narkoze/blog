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
              {{ $t('title', { name: image.name }) }}
              <spinner v-if="disabled"></spinner>
            </h1>

            <div class="columns">
              <div class="column is-9">
                <div class="field image-container-medium">
                  <img
                    v-if="image.hasOwnProperty('images')"
                    @click="showPhotoswipe = true"
                    :src="image.images.medium"
                    class="image is-medium"
                  >
                </div>

                <div class="field">
                  <label :class="['label', { 'has-text-danger': errors.name }]">
                    <p class="control">
                      <input
                        v-model="image.name"
                        type="text"
                        :class="['input', { 'is-danger': errors.name }]"
                        :disabled="disabled"
                      >
                    </p>
                  </label>
                  <p v-if="errors.name" class="help is-danger">{{ errors.name.join() }}</p>
                </div>

                <a
                  @click="updating || update()"
                  :class="['button is-info', { 'is-loading': updating }]"
                  :disabled="updating || destroying"
                >
                  <span>{{ $t('update') }}</span>
                </a>

                <a
                  @click="showModalConfirm = true"
                  :class="['button is-danger is-inverted is-pulled-right', { 'is-loading': destroying }]"
                  :disabled="destroying"
                >
                  <span>{{ $t('destroy') }}</span>
                </a>
              </div>

              <div class="column is-3">
                <div class="card">
                  <header class="card-header">
                    <p class="card-header-title">
                      {{ $t('infocard.title') }}
                    </p>
                  </header>
                  <div class="card-content">
                    <div class="content">
                      <ul class="infocard">
                        <li class="columns is-mobile is-gapless is-marginless">
                          <div class="column">{{ $t('infocard.author') }}</div>
                          <div class="column has-text-right">{{ image.author.name }}</div>
                        </li>

                        <li class="columns is-mobile is-gapless is-marginless">
                          <div class="column">{{ $t('infocard.created_at') }}</div>
                          <div class="column has-text-right">{{ image.created_at | dateString }}</div>
                        </li>

                        <li class="columns is-mobile is-gapless is-marginless">
                          <div class="column">{{ $t('infocard.updated_at') }}</div>
                          <div class="column has-text-right">{{ image.updated_at | dateString }}</div>
                        </li>

                        <li class="columns is-mobile is-gapless is-marginless">
                          <div class="column">{{ $t('infocard.type') }}</div>
                          <div class="column has-text-right">{{ image.type }}</div>
                        </li>

                        <li class="columns is-mobile is-gapless is-marginless">
                          <div class="column">{{ $t('infocard.size') }}</div>
                          <div class="column has-text-right">{{ image.size | filesize }}</div>
                        </li>

                        <li class="columns is-mobile is-gapless is-marginless">
                          <div class="column">{{ $t('infocard.dimensions') }}</div>
                          <div class="column has-text-right">{{ image.dimensions }}</div>
                        </li>

                        <li class="columns is-mobile is-gapless is-marginless">
                          <div class="column">{{ $t('infocard.model') }}</div>
                          <div class="column has-text-right">{{ image.model || '-' }}</div>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
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
      {{ $t('destroy.confirm', { name: image.name }) }}
    </modal-confirm>

    <photoswipe
      v-if="showPhotoswipe"
      :items="[{
        title: image.name,
        src: image.images.original,
        w: image.width,
        h: image.height
      }]"
      @close="showPhotoswipe = false"
    >
    </photoswipe>
  </div>
</template>

<script>
  import ModalConfirm from '../../../modals/modal-confirm.vue'
  import ErrorHandler from '../../../../mixins/error-handler'
  import Photoswipe from '../../../photoswipe.vue'
  import Spinner from '../../../spinner.vue'

  export default {
    components: {
      ModalConfirm,
      Photoswipe,
      Spinner
    },
    mixins: [
      ErrorHandler,
    ],
    data: function () {
      return {
        image: this.$route.params.image || {},
        updating: false,
        destroying: false,
        showModalConfirm: false,
        showPhotoswipe: false
      }
    },
    created () {
      if (this.image.id === undefined && this.$route.params.id) {
        this.get()
      }
    },
    methods: {
      get () {
        this.disabled = true

        this.$axios
          .get(`admin/image/${this.$route.params.id}`)
          .then(response => {
            this.disabled = false
            this.image = response.data.data
          })
          .catch(this.handleError)
      },
      update () {
        this.disabled = this.updating = true

        this.$axios
          .put(`admin/image/${this.image.id}`, this.image)
          .then(response => {
            this.disabled = this.updating = false
            this.image = response.data.data
            this.$root.notify('success', this.$t('update.success'))
          })
          .catch(error => {
            this.updating = false
            this.handleError(error)
          })
      },
      destroy () {
        this.disabled = this.destroying = true

        this.$axios
          .delete(`admin/image/${this.image.id}`)
          .then(() => {
            this.$router.push({ name: 'admin-images' })
            this.$root.notify('success', this.$t('destroy.success'))
          })
          .catch(this.handleError)
      }
    }
  }
</script>

<i18n>
  {
    "en": {
      "destroy.confirm": "Delete {name}?",
      "destroy.success": "Image successfully deleted",
      "destroy": "Delete",
      "title": "Image {name}",
      "update.success": "Image successfully updated",
      "update": "Update",
      "infocard": {
        "title": "Information",
        "author": "Author",
        "type": "Type",
        "size": "Size",
        "dimensions": "Dimensions",
        "model": "Taken with",
        "created_at": "Uploaded",
        "updated_at": "Updated"
      }
    },
    "lv": {
      "destroy.confirm": "Dzēst {name}?",
      "destroy.success": "Attēls veiksmīgi izdzēsts",
      "destroy": "Dzēst",
      "title": "Attēls {name}",
      "update.success": "Attēls veiksmīgi atjaunots",
      "update": "Atjaunināt",
      "infocard": {
        "title": "Informācija",
        "author": "Autors",
        "type": "Tips",
        "size": "Lielums",
        "dimensions": "Izmērs",
        "model": "Uzņemts ar",
        "created_at": "Augšupielādēts",
        "updated_at": "Atjaunots"
      }
    }
  }
</i18n>
