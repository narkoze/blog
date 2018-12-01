<template>
  <div>
    <h1 class="title">
      {{ $t('title') }}
      <spinner v-if="disabled"></spinner>
    </h1>

    <div class="field">
      <a
        @click="$refs.file.click()"
        class="button is-primary"
      >
        <span>
          <i class="fas fa-plus"></i>
          {{ $t('image.upload') }}
        </span>
      </a>
    </div>

    <div
      @click.self="!images.length && $refs.file.click()"
      :class="['droparea field', { 'is-pointer': !images.length }]"
      ref="droparea"
      id="droparea"
    >
      <div class="images">
        <div
          v-for="(image, i) in images"
          :key="i"
          :class="['image', {
            'is-error': image.src && image.image.size > 5242880
          }]"
        >
          <a
            v-if="!image.uploading"
            @click="images.splice(i, 1)"
            :title="$t('remove')"
            class="delete is-medium is-for-image"
          >
          </a>
          <div
            v-if="image.uploading && !image.uploaded"
            @click="cancelUpload"
            :title="$t('cancel')"
            class="is-for-image is-24x24 cancel"
          >
            <i class="far fa-stop-circle fa-lg has-text-grey"></i>
          </div>

          <img
            v-if="image.src"
            @click.self="() => {
              imagePreviewIndex = i
              showPhotoswipe = true
            }"
            :src="image.src"
            :style="{ filter: image.uploading && `grayscale(${100 - image.progressValue}%)` }"
          >
          <div
            v-else
            class="loading has-text-centered"
          >
            <spinner class="is-large"></spinner>
          </div>

          <circlebar
            :id="i"
            :value="image.progressValue"
            :uploaded="image.uploaded"
          >
          </circlebar>

          <div
            v-if="image.src && image.image.size > 5242880"
            class="image-error"
          >
            {{ $t('toolarge') }}
          </div>

          <input
            v-model="image.name"
            type="text"
            :class="['input', {
              'is-danger': !image.name || image.errors.hasOwnProperty('name')
            }]"
            :disabled="image.disabled || image.uploaded"
          >
        </div>
      </div>

      <div
        v-if="!images.length && !isDragOver"
        class="drop has-text-info"
      >
        <i class="fas fa-cloud-download-alt fa-10x is-block"></i>
        <div class="has-text-centered">
          {{ $t('drophere') }}
        </div>
      </div>

      <input
        ref="file"
        type="file"
        accept="image/*"
        title=" "
        class="file"
        multiple
        @change="addImages"
      />
    </div>

    <a
      v-if="cancel"
      @click="cancelUpload"
      class="button is-danger"
    >
      {{ $t('cancel') }}
    </a>

    <a
      v-else
      @click="upload"
      class="button is-info"
      :disabled="!images.length"
    >
      {{ $t('upload') }}
    </a>

    <photoswipe
      v-if="showPhotoswipe"
      :items="imagesPreview"
      :index="imagePreviewIndex"
      @close="showPhotoswipe = false"
    >
    </photoswipe>
  </div>
</template>

<script>
  import ErrorHandler from '../../../../../mixins/error-handler'
  import Photoswipe from '../../../../photoswipe.vue'
  import Circlebar from '../../../../circlebar.vue'
  import Spinner from '../../../../spinner.vue'
  import axios from 'axios'
  import exif from 'exif-js'

  export default {
    components: {
      Spinner,
      Photoswipe,
      Circlebar
    },
    mixins: [
      ErrorHandler,
    ],
    data: () => ({
      images: [],
      isDragOver: false,
      isDragOverFile: false,
      imagePreviewIndex: 0,
      showPhotoswipe: false,
      uploadInLoop: false,
      cancel: null
    }),
    mounted () {
      let droparea = this.$refs.droparea
      droparea.addEventListener('dragover', this.dragover)
      droparea.addEventListener('dragleave', this.dragleave)
      droparea.addEventListener('drop', this.dragleave)
      droparea.addEventListener('mouseenter', this.mouseenter)
      droparea.addEventListener('scroll', this.scroll)
    },
    computed: {
      imagesPreview () {
        let images = []
        this.images.forEach(image => {
          images.push({
            title: image.name,
            src: image.src,
            w: image.width,
            h: image.height
          })
        })
        return images
      }
    },
    watch: {
      uploadInLoop (bool) {
        if (!bool) this.$root.notify('success', this.$t('uploadfinished'))
      }
    },
    methods: {
      addImages (e) {
        Array.from(e.target.files).forEach(file => {
          if (!file.size) return
          if (!file.type.match('image.*')) return

          let image = {
            name: this.$options.filters.filename(file.name),
            image: file,
            progressValue: 0,
            uploading: false,
            uploaded: false,
            disabled: false,
            errors: {}
          }

          this.$set(image, 'src', null)
          this.$set(image, 'width', 0)
          this.$set(image, 'height', 0)

          this.images.push(image)

          let reader = new FileReader()
          reader.onloadend = () => {
            let img = new Image()
            img.onload = () => {
              let orientation = null
              exif.getData(img, () => {
                orientation = exif.getAllTags(img).Orientation
              })
              if (orientation > 4 && orientation < 9) {
                let imgFixed = this.orientate(img, img.width, img.height, orientation)
                image.src = imgFixed.src
                image.width = imgFixed.width
                image.height = imgFixed.height
              } else {
                image.src = img.src
                image.width = img.width
                image.height = img.height
              }
            }
            img.src = reader.result
          }
          reader.readAsDataURL(file)
        })
      },
      upload () {
        let image = null
        this.images.forEach(file => {
          if (image) return
          if (!file.name || file.name.length > 255 || file.image.size > 5242880) return
          if (!file.uploaded) image = file
        })
        if (!image) {
          this.uploadInLoop = false
          return
        }

        this.uploadInLoop = true

        this.cancel = null
        image.disabled = image.uploading = true
        image.progressValue = 0
        image.errors = {}

        const data = new FormData()
        data.append('image', image.image)
        data.append('name', image.name)

        const CancelToken = axios.CancelToken
        axios
          .post('admin/image', data, {
            cancelToken: new CancelToken(cancel => {
              this.cancel = cancel
            }),
            onUploadProgress: progress => {
              image.progressValue = parseInt(Math.round((progress.loaded * 100) / progress.total))
            }
          })
          .then(response => {
            this.cancel = null
            image.disabled = image.uploading = false
            image.uploaded = true

            this.upload()
            this.$emit('uploaded')
          })
          .catch(error => {
            this.cancel = null

            image.disabled = image.uploading = false

            if (!axios.isCancel(error)) {
              this.uploadInLoop = false

              if (error.response.status === 422) {
                image.errors = error.response.data.errors
              } else {
                this.handleError(error)
              }
            }
          })
      },
      cancelUpload () {
        this.cancel()

        this.images.forEach(image => {
          if (!image.uploaded) image.uploading = false
        })
      },
      orientate (image, width, height, orientation) {
        let canvas = document.createElement('canvas')
        canvas.width = image.height
        canvas.height = image.width

        let ctx = canvas.getContext('2d')
        switch (orientation) {
          case 2: ctx.transform(-1, 0, 0, 1, image.width, 0); break
          case 3: ctx.transform(-1, 0, 0, -1, image.width, image.height); break
          case 4: ctx.transform(1, 0, 0, -1, 0, image.height); break
          case 5: ctx.transform(0, 1, 1, 0, 0, 0); break
          case 6: ctx.transform(0, 1, -1, 0, image.height, 0); break
          case 7: ctx.transform(0, -1, -1, 0, image.height, image.width); break
          case 8: ctx.transform(0, -1, 1, 0, 0, image.width); break
          default: ctx.transform(1, 0, 0, 1, 0, 0)
        }
        ctx.drawImage(image, 0, 0, image.width, image.height)

        return {
          src: canvas.toDataURL('image/jpeg', 0.1),
          width: canvas.width,
          height: canvas.height
        }
      },
      mouseenter (e) {
        this.$refs.file.style.visibility = 'hidden'
      },
      dragover (e) {
        this.$refs.file.style.visibility = 'visible'
        this.$refs.droparea.style['border-color'] = '#7a7a7a'
        this.$refs.droparea.style['background-color'] = 'hsl(0, 0%, 92%)'
        this.isDragOver = true
      },
      dragleave (e) {
        this.$refs.droparea.style['border-color'] = '#209cee'
        this.$refs.droparea.style['background-color'] = 'white'
        this.isDragOver = false
      },
      scroll () {
        this.$refs.file.style.height = '68vh'
        this.$refs.file.style.height = `${this.$refs.droparea.scrollHeight}px`
      }
    },
    beforeDestroy () {
      if (this.cancel) this.cancelUpload()
    }
  }
</script>

<i18n>
  {
    "en": {
      "title": "Image upload",
      "image.upload": "Select images",
      "drophere": "Click or Drop images here",
      "upload": "Upload",
      "remove": "Remove",
      "cancel": "Cancel",
      "toolarge": "Image is too large, max 5MB",
      "uploadfinished": "Images successfully uploaded"
    },
    "lv": {
      "title": "Attēlu augšupielāde",
      "image.upload": "Izvēlēties attēlus",
      "drophere": "Spied vai Met attēlus šeit",
      "upload": "Augšupielādēt",
      "remove": "Noņemt",
      "cancel": "Atcelt",
      "toolarge": "Attēls pārāk liels, max 5MB",
      "uploadfinished": "Attēli veiksmīgi augšupielādēti"
    }
  }
</i18n>
