<template>
  <div class="modal is-active">
    <div
      @click="$emit('close')"
      class="modal-background"
    >
    </div>

    <div class="modal-content is-fullscreen">
      <div class="box">
        <h1 class="title">
          {{ $t('title') }}
          <spinner v-if="disabled"></spinner>
        </h1>

        <div class="field">
          <a
            @click="showModalImageUpload = true"
            class="button is-primary"
          >
            <span>
              <i class="fas fa-plus"></i>
              {{ $t('image.upload') }}
            </span>
          </a>
        </div>

        <div class="field">
          <label class="label">{{ $t('search') }}
            <p class="control has-icons-right">
              <input
                :value="params.search"
                @input="search"
                type="text"
                class="input"
              >
              <span
                v-if="params.search"
                @click="() => {
                  params.search = ''
                  get()
                }"
                class="icon is-right is-clickable"
              >
                <i class="fas fa-times"></i>
              </span>
            </p>
          </label>
        </div>

        <div class="selectarea field">
          <div class="images">
            <div
              v-for="(image, i) in images"
              :key="i"
              class="image"
            >
              <input
                type="checkbox"
                :class="['is-checkradio', {
                  'has-background-color is-info': selectedImages.filter(img => img.id === image.id).length > 0
                }]"
                :checked="selectedImages.filter(img => img.id === image.id).length > 0"
              >
              <label class="image-checkbox"></label>

              <img
                @click="toggle(image)"
                :src="image.images.medium"
              >
            </div>
          </div>
        </div>

        <div class="field">
          <pagination
            :paginator="paginator"
            :changing="pageChanging"
            @changed="page => {
              pageChanging = true
              get(page)
            }"
          >
          </pagination>
        </div>

        <a
          @click="$emit('selected', selectedImages)"
          class="button is-info"
          :disabled="!selectedImages.length"
        >
          {{ $t('insert') }}
        </a>
      </div>
    </div>

    <button
      @click="$emit('close')"
      class="modal-close is-large"
    >
    </button>

    <modal-image-upload
      v-if="showModalImageUpload"
      @close="closeImageUpload"
      @uploaded="imagesWasUploaded = true"
    >
    </modal-image-upload>
  </div>
</template>

<script>
  import ModalImageUpload from './modal-image-upload.vue'
  import ErrorHandler from '../../mixins/error-handler'
  import PageHandler from '../../mixins/page-handler'
  import Pagination from '../pagination.vue'
  import debounce from 'lodash/debounce'
  import Spinner from '../spinner.vue'
  import axios from 'axios'

  export default {
    components: {
      ModalImageUpload,
      Pagination,
      Spinner
    },
    mixins: [
      ErrorHandler,
      PageHandler
    ],
    data: () => ({
      images: [],
      selectedImages: [],
      showModalImageUpload: false,
      imagesWasUploaded: false,
      scrollY: null
    }),
    created () {
      this.scrollY = window.scrollY
      window.addEventListener('scroll', this.lockScroll)

      this.get()
    },
    methods: {
      get (page = 1) {
        this.disabled = true
        this.params.page = page

        axios
          .get('admin/image', { params: this.params })
          .then(response => {
            this.disabled = false
            this.images = response.data.data
            this.handlePage(response.data.meta, response.data.links)
          })
          .catch(error => {
            this.sorting = this.pageChanging = false
            this.handleError(error)
          })
      },
      search: debounce(function (e) {
        this.params.search = e.target.value
        this.get()
      }, 500),
      closeImageUpload () {
        this.showModalImageUpload = false

        if (this.imagesWasUploaded) {
          this.imagesWasUploaded = false
          this.get()
        }
      },
      toggle (image) {
        if (this.selectedImages.filter(img => img.id === image.id).length > 0) {
          this.selectedImages = this.selectedImages.filter(img => img.id !== image.id)
        } else {
          this.selectedImages.push(image)
        }
      },
      lockScroll () {
        window.scrollTo(window.scrollX, this.scrollY)
      }
    },
    beforeDestroy () {
      window.removeEventListener('scroll', this.lockScroll)
    }
  }
</script>

<i18n>
  {
    "en": {
      "title": "Image menu",
      "image.upload": "Upload images",
      "insert": "Insert",
      "search": "Search"
    },
    "lv": {
      "title": "Attēlu izvēlne",
      "image.upload": "Augšupielādēt attēlus",
      "insert": "Ievietot",
      "search": "Meklēt"
    }
  }
</i18n>
