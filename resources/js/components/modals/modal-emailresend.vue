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

        <article class="message is-danger">
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
    methods: {
      resend () {
        this.disabled = true

        axios
          .get(`emailresend/${this.$root.auth.user.id}`)
          .then(() => {
            this.$emit('close')
          })
          .catch(this.handleError)
      }
    }
  }
</script>

<i18n>
  {
    "en": {
      "title": "Verify your e-mail address",
      "beforeproceeding": "Before proceeding, please check your email for a verification link",
      "resend": "Send a verification email"
    },
    "lv": {
      "title": "Aplieciniet savu e-pasta adresi",
      "beforeproceeding": "Pirms turpiniet, lūdzu pārbaudiet e-pastu vai jums jau nav apliecinājuma saite",
      "resend": "Sūtīt apliecinājuma e-pastu"
    }
  }
</i18n>
