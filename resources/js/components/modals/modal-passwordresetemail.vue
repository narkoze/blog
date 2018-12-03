<template>
  <div class="modal is-active">
    <div
      @click="$emit('close')"
      class="modal-background"
    >
    </div>

    <div class="modal-content">
      <div class="box">
        <h1 class="title">{{ $t('title')}}</h1>

        <div class="field">
          <label :class="['label', { 'has-text-danger': errors.email }]">
            {{ $t('email') }}
            <p class="control has-icons-left">
              <input
                v-model="email"
                @keyup.enter="sendResetLink"
                name="email"
                type="email"
                :class="['input', { 'is-danger': errors.email }]"
                :disabled="disabled"
              >
              <span class="icon is-small is-left">
                <i class="fas fa-envelope"></i>
              </span>
            </p>
          </label>
          <p v-if="errors.email" class="help is-danger">{{ errors.email.join() }}</p>
        </div>

        <a
          @click="sendResetLink"
          :class="['button is-info', { 'is-loading': disabled }]"
          :disabled="disabled"
        >
          <span>{{ $t('sendresetlink') }}</span>
        </a>
      </div>
    </div>

    <button
      @click="$emit('close')"
      class="modal-close is-large"
    >
    </button>
  </div>
</template>

<script>
  import ErrorHandler from '../../mixins/error-handler'
  import axios from 'axios'

  export default {
    mixins: [
      ErrorHandler,
    ],
    data: () => ({
      email: null,
      scrollY: null
    }),
    created () {
      this.scrollY = window.scrollY
      window.addEventListener('scroll', this.lockScroll)
    },
    methods: {
      sendResetLink () {
        this.disabled = true
        this.errors = {}

        axios
          .post('passwordresetemail', {
            email: this.email
          })
          .then(() => {
            this.$emit('close')

            this.$root.notify('success', this.$t('sendResetLink.success'))
          })
          .catch(this.handleError)
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
      "title": "Reset password",
      "email": "E-mail",
      "sendresetlink": "Send reset e-mail",
      "sendResetLink.success": "Reset link successfully has been sent to your e-mail address"
    },
    "lv": {
      "title": "Atiestatīt paroli",
      "email": "E-pasts",
      "sendresetlink": "Sūtīt atiestatīšanas e-pastu",
      "sendResetLink.success": "Atiestatīšanas saite veiksmīgi nosūtīta uz jūsu e-pasta adresi"
    }
  }
</i18n>
