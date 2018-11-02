<template>
  <section class="section">
    <div class="container">
      <div class="columns">
        <div class="column is-half is-offset-one-quarter">
          <h1 class="title">{{ $t('title') }}</h1>

          <div class="field">
            <label class="label">
              {{ $t('email') }}
              <p class="control has-icons-left">
                <input
                  v-model="email"
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

          <div class="field">
            <label class="label">
              {{ $t('password') }}
              <p class="control has-icons-left">
                <input
                  v-model="password"
                  type="password"
                  :class="['input', { 'is-danger': errors.password }]"
                  :disabled="disabled"
                >
                <span class="icon is-small is-left">
                  <i class="fas fa-lock"></i>
                </span>
              </p>
            </label>
            <p v-if="errors.password" class="help is-danger">{{ errors.password.join() }}</p>
          </div>

          <div class="field">
            <label class="label">
              {{ $t('password_confirmation') }}
              <p class="control has-icons-left">
                <input
                  v-model="password_confirmation"
                  @keyup.enter="reset"
                  type="password"
                  :class="['input', { 'is-danger': errors.password_confirmation }]"
                  :disabled="disabled"
                >
                <span class="icon is-small is-left">
                  <i class="fas fa-lock"></i>
                </span>
              </p>
            </label>
            <p v-if="errors.password_confirmation" class="help is-danger">{{ errors.password_confirmation.join() }}</p>
          </div>

          <a
            @click="reset"
            :class="['button is-info', { 'is-loading': disabled }]"
            :disabled="disabled"
          >
            <span>{{ $t('reset') }}</span>
          </a>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
  import ErrorHandler from '../../../mixins/error-handler'
  import axios from 'axios'

  export default {
    mixins: [
      ErrorHandler,
    ],
    data: function () {
      return {
        email: this.$route.params.email,
        password: null,
        password_confirmation: null
      }
    },
    methods: {
      reset () {
        this.disabled = true
        this.errors = {}

        axios
          .post('passwordreset', {
            email: this.email,
            password: this.password,
            password_confirmation: this.password_confirmation,
            token: this.$route.params.token
          })
          .then(() => {
            this.$router.push({ name: 'home' })
            this.$root.showModalSignin = true
          })
          .catch(this.handleError)
      }
    }
  }
</script>

<i18n>
  {
    "en": {
      "title": "Reset the password",
      "email": "E-mail",
      "password": "Password",
      "password_confirmation": "Password again",
      "reset": "Reset"
    },
    "lv": {
      "title": "Atiestatīt paroli",
      "email": "E-pasts",
      "password": "Parole",
      "password_confirmation": "Parole vēlreiz",
      "reset": "Atiestatīt"
    }
  }
</i18n>
