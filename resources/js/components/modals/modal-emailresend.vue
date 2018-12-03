<template>
  <div class="modal is-active">
    <div
      @click="$emit('close')"
      class="modal-background"
    >
    </div>

    <div class="modal-content">
      <div class="box">
        <h1 class="title">{{ $t('title') }}</h1>

        <article class="message is-warning">
          <div class="message-body">
            {{ $t('beforeproceeding') }}
          </div>
        </article>

        <a
          @click="resend"
          :class="['button is-info', { 'is-loading': disabled }]"
          :disabled="disabled"
        >
          <span>{{ $t('resend') }}</span>
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
      scrollY: null
    }),
    created () {
      this.scrollY = window.scrollY
      window.addEventListener('scroll', this.lockScroll)
    },
    methods: {
      resend () {
        this.disabled = true

        axios
          .get(`emailresend/${this.$root.user.id}`)
          .then(() => {
            this.$emit('close')

            this.$root.notify('success', this.$t('resend.success'))
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
      "title": "Verify your e-mail address",
      "beforeproceeding": "Before proceeding, please check your email for a verification link",
      "resend": "Send a verification email",
      "resend.success": "Verification link has been successfully sent"
    },
    "lv": {
      "title": "Aplieciniet savu e-pasta adresi",
      "beforeproceeding": "Pirms turpiniet, lūdzu pārbaudiet e-pastu vai jums jau nav apliecinājuma saite",
      "resend": "Sūtīt apliecinājuma e-pastu",
      "resend.success": "Apliecinājuma saite veiksmīgi nosūtīta"
    }
  }
</i18n>
