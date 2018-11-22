<template>
  <div>
    <transition
      name="fade"
      appear
    >
      <section class="section">
        <div class="card is-medium">
          <div class="card-content">
            <div class="content">
              <h1 class="title">
                {{ $t('title') }}
                <spinner v-if="disabled"></spinner>
              </h1>

              <div
                v-if="user.hasOwnProperty('email_verified') && !user.email_verified"
                class="field"
              >
                <a
                  @click="$root.showModalEmailresend = true"
                  class="button is-warning is-fullwidth"
                >
                  <span class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                  </span>
                  <span>{{ $t('verify') }}</span>
                </a>
              </div>

              <div class="field is-horizontal">
                <div class="field-label is-normal">
                  <label
                    :class="['label', { 'has-text-danger': errors.image }]"
                    for="image"
                  >
                    {{ $t('image') }}
                  </label>
                </div>
                <div class="field-body is-block">
                  <div class="image-container">
                    <img
                      v-if="user.image"
                      @click="(destroyingImage || uploadingImage) || (showPhotoswipe = true)"
                      :src="user.image && `${user.images.medium}`"
                      class="profile"
                    >

                    <i
                      v-else
                      @click="uploadingImage || $refs.file.click()"
                      class="fas fa-user has-text-grey-lighter fa-10x has-text-centered is-pointer"
                    >
                    </i>
                  </div>
                  <p v-if="errors.image" class="help is-danger">{{ errors.image.join() }}</p>

                  <input
                    id="image"
                    type="file"
                    accept="image/*"
                    title=" "
                    ref="file"
                    class="is-sr-only"
                    @change="uploadImage"
                  />
                  <a
                    @click="(destroyingImage || uploadingImage) || $refs.file.click()"
                    :class="['button is-small is-dark', { 'is-loading': uploadingImage }]"
                    :disabled="destroyingImage || uploadingImage"
                  >
                    {{ user.image ? $t('image.change') : $t('image.upload') }}
                  </a>
                  <a
                    v-if="user.image"
                    @click="(destroyingImage || uploadingImage) || (showModalConfirm = 'destroyImage.confirm')"
                    class="button is-small is-danger is-inverted"
                    :disabled="destroyingImage || uploadingImage"
                  >
                    {{ $t('image.destroy') }}
                  </a>
                </div>
              </div>

              <div class="field is-horizontal">
                <div class="field-label is-normal">
                  <label
                    :class="['label', { 'has-text-danger': errors.name }]"
                    for="name"
                  >
                    {{ $t('name') }}
                  </label>
                </div>
                <div class="field-body">
                  <div class="field">
                    <p class="control has-icons-left">
                      <input
                        id="name"
                        v-model="user.name"
                        type="text"
                        :class="['input', { 'is-danger': errors.name }]"
                        :disabled="disabled"
                      >
                      <span class="icon is-small is-left">
                        <i class="fas fa-user"></i>
                      </span>
                    </p>
                    <p v-if="errors.name" class="help is-danger">{{ errors.name.join() }}</p>
                  </div>
                </div>
              </div>

              <div class="field is-horizontal">
                <div class="field-label is-normal">
                  <label
                    :class="['label', { 'has-text-danger': errors.email }]"
                    for="email"
                  >
                    {{ $t('email') }}
                  </label>
                </div>
                <div class="field-body">
                  <div class="field">
                    <p class="control has-icons-left">
                      <input
                        id="email"
                        v-model="user.email"
                        type="email"
                        :class="['input', { 'is-danger': errors.email }]"
                        :disabled="disabled"
                      >
                      <span class="icon is-small is-left">
                        <i class="fas fa-envelope"></i>
                      </span>
                    </p>
                    <p v-if="errors.email" class="help is-danger">{{ errors.email.join() }}</p>
                  </div>
                </div>
              </div>

              <div class="field is-horizontal">
                <div class="field-label is-normal">
                  <label :class="['label', { 'has-text-danger': errors.locale }]">
                    {{ $t('locale') }}
                  </label>
                </div>
                <div class="field-body">
                  <div class="field">
                    <input
                      v-model="user.locale"
                      value="en"
                      type="radio"
                      class="is-checkradio is-info"
                      id="en"
                    >
                    <label for="en">English</label>

                    <input
                      v-model="user.locale"
                      value="lv"
                      type="radio"
                      class="is-checkradio is-info"
                      id="lv"
                    >
                    <label for="lv">Latviešu</label>
                    <p v-if="errors.locale" class="help is-danger">{{ errors.locale.join() }}</p>
                  </div>
                </div>
              </div>

              <div class="field is-horizontal">
                <div class="field-label is-normal">
                  <label
                    :class="['label', { 'has-text-danger': errors.password }]"
                    for="password"
                  >
                    {{ $t('password') }}
                  </label>
                </div>
                <div class="field-body">
                  <div class="field">
                    <p class="control has-icons-left">
                      <input
                        id="password"
                        v-model="user.password"
                        type="password"
                        :class="['input', { 'is-danger': errors.password }]"
                        :disabled="disabled"
                      >
                      <span class="icon is-small is-left">
                        <i class="fas fa-lock"></i>
                      </span>
                    </p>
                    <p v-if="errors.password" class="help is-danger">{{ errors.password.join() }}</p>
                  </div>
                </div>
              </div>

              <div class="field is-horizontal">
                <div class="field-label is-normal">
                  <label
                    :class="['label', { 'has-text-danger': errors.new_password }]"
                    for="new_password"
                  >
                    {{ $t('new_password') }}
                  </label>
                </div>
                <div class="field-body">
                  <div class="field">
                    <p class="control has-icons-left">
                      <input
                        id="new_password"
                        v-model="user.new_password"
                        type="password"
                        :class="['input', { 'is-danger': errors.new_password }]"
                        :disabled="disabled"
                      >
                      <span class="icon is-small is-left">
                        <i class="fas fa-lock"></i>
                      </span>
                    </p>
                    <p v-if="errors.new_password" class="help is-danger">{{ errors.new_password.join() }}</p>
                  </div>
                </div>
              </div>

              <div class="field is-horizontal">
                <div class="field-label is-normal">
                  <label
                    :class="['label', { 'has-text-danger': errors.new_password_confirmation }]"
                    for="new_password_confirmation"
                  >
                    {{ $t('new_password_confirmation') }}
                  </label>
                </div>
                <div class="field-body">
                  <div class="field">
                    <p class="control has-icons-left">
                      <input
                        id="new_password_confirmation"
                        v-model="user.new_password_confirmation"
                        type="password"
                        :class="['input', { 'is-danger': errors.new_password_confirmation }]"
                        :disabled="disabled"
                      >
                      <span class="icon is-small is-left">
                        <i class="fas fa-lock"></i>
                      </span>
                    </p>
                    <p v-if="errors.new_password_confirmation" class="help is-danger">{{ errors.new_password_confirmation.join() }}</p>
                  </div>
                </div>
              </div>

              <div class="field is-horizontal">
                <div class="field-label is-normal"></div>
                <div class="field-body is-block">
                  <div class="buttons is-block">
                    <a
                      @click="disabled || update()"
                      :class="['button is-info', { 'is-loading': updating }]"
                      :disabled="disabled"
                    >
                      <span>{{ $t('update') }}</span>
                    </a>

                    <a
                      @click="disabled || (showModalConfirm = 'destroy.confirm')"
                      :class="['button is-danger is-inverted is-pulled-right', { 'is-loading': destroying }]"
                      :disabled="disabled"
                    >
                      <span>{{ $t('destroy') }}</span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </transition>

    <modal-confirm
      v-if="showModalConfirm"
      @confirmed="confirm"
      @close="showModalConfirm = false"
    >
      {{ $t(showModalConfirm) }}
    </modal-confirm>

    <photoswipe
      v-if="showPhotoswipe"
      :items="[{
        title: user.name,
        src: user.images.original,
        w: user.image.width,
        h: user.image.height
      }]"
      @close="showPhotoswipe = false"
    >
    </photoswipe>
  </div>
</template>

<script>
  import LocaleHandler from '../../mixins/locale-handler'
  import ModalConfirm from '../modals/modal-confirm.vue'
  import ErrorHandler from '../../mixins/error-handler'
  import Photoswipe from '../photoswipe.vue'
  import Spinner from '../spinner.vue'
  import axios from 'axios'

  export default {
    components: {
      ModalConfirm,
      Photoswipe,
      Spinner
    },
    mixins: [
      LocaleHandler,
      ErrorHandler,
    ],
    data: () => ({
      user: {},
      updating: false,
      destroying: false,
      showModalConfirm: false,
      uploadingImage: false,
      showPhotoswipe: false,
      destroyingImage: false
    }),
    created () {
      this.get()
    },
    methods: {
      get () {
        this.disabled = true
        this.errors = {}

        axios
          .get('profile')
          .then(response => {
            this.disabled = false

            let user = response.data.data
            this.user = user
            this.$root.user = user
            localStorage.setItem('user', JSON.stringify(user))
          })
          .catch(this.handleError)
      },
      update () {
        this.disabled = this.updating = true
        this.errors = {}

        axios
          .put('profile', this.user)
          .then(response => {
            this.disabled = this.updating = false

            let user = response.data.data
            this.user = this.$root.user = user
            localStorage.setItem('user', JSON.stringify(user))

            this.handleLocale(user.locale)

            this.$root.notify('success', this.$t('update.success'))
          })
          .catch(error => {
            this.updating = false
            this.handleError(error)
          })
      },
      destroy () {
        this.disabled = this.destroying = true

        axios
          .delete('profile')
          .then(() => {
            localStorage.removeItem('token')

            this.$root.user = null
            localStorage.removeItem('user')

            this.$router.push({ name: 'home' })

            this.$root.notify('success', this.$t('destroy.success'))
          })
          .catch(error => {
            this.disabled = this.destroying = false
            this.handleError(error)
          })
      },
      uploadImage (e) {
        this.uploadingImage = true

        const data = new FormData()
        data.append('image', e.target.files[0])

        axios
          .post('profile/image', data)
          .then(response => {
            this.uploadingImage = false

            this.user.image = response.data.image
            this.user.images = response.data.images
          })
          .catch(error => {
            this.uploadingImage = false
            this.handleError(error)
          })
      },
      destroyImage () {
        this.destroyingImage = true

        axios
          .delete('profile/image')
          .then(() => {
            this.destroyingImage = false

            this.user.image = null
            this.user.images = null
          })
          .catch(error => {
            this.destroyingImage = false
            this.handleError(error)
          })
      },
      confirm () {
        switch (this.showModalConfirm) {
          case 'destroy.confirm': this.destroy(); break
          case 'destroyImage.confirm': this.destroyImage(); break
        }
        this.showModalConfirm = false
      }
    }
  }
</script>

<i18n>
  {
    "en": {
      "title": "Profile",
      "name": "Name",
      "email": "E-mail",
      "password": "Current password",
      "new_password": "New password",
      "new_password_confirmation": "New password again",
      "update": "Update",
      "destroy": "Delete profile",
      "update.success": "Profile successfully updated",
      "destroy.success": "Profile successfully destroyed",
      "destroy.confirm": "Delete profile?",
      "verify": "Verify your e-mail address",
      "image": "Picture",
      "image.change": "Change picture",
      "image.upload": "Upload picture",
      "image.destroy": "Delete picture",
      "destroyImage.confirm": "Delete image?",
      "locale": "Preferred language"
    },
    "lv": {
      "title": "Profils",
      "name": "Vārds",
      "email": "E-pasts",
      "password": "Esošā parole",
      "new_password": "Jaunā parole",
      "new_password_confirmation": "Jaunā parole vēlreiz",
      "update": "Atjaunot",
      "destroy": "Dzēst profilu",
      "update.success": "Profils veiksmīgi atjaunots",
      "destroy.success": "Profils veiksmīgi izdzēsts",
      "destroy.confirm": "Dzēst profilu?",
      "verify": "Aplieciniet savu e-pasta adresi",
      "image": "Bilde",
      "image.change": "Mainīt bildi",
      "image.upload": "Augšupielādēt bildi",
      "image.destroy": "Dzēst bildi",
      "destroyImage.confirm": "Dzēst bildi?",
      "locale": "Vēlamā valoda"
    }
  }
</i18n>
