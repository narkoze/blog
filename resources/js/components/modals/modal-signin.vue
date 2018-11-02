<template>
  <div class="modal is-active">
    <div
      @click="$emit('close')"
      class="modal-background"
    >
    </div>

    <div class="modal-content">
      <div class="box">
        <h1 class="title">{{ $t('signin') }}</h1>

        <form>
          <div class="field">
            <label class="label">
              {{ $t('email') }}
              <p class="control has-icons-left">
                <input
                  v-model="email"
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
            <p v-if="errors.error" class="help is-danger">{{ $tc(errors.error) ? $t(errors.error) : errors.message }}</p>
            <p v-if="errors.email" class="help is-danger">{{ errors.email.join() }}</p>
          </div>

          <div class="field">
            <label class="label">
              {{ $t('password') }}
              <p class="control has-icons-left">
                <input
                  v-model="password"
                  @keyup.enter="signin"
                  name="password"
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
            <a @click="$root.showModalPasswordresetemail = true">
              <small>{{ $t('forgotpassword') }}</small>
            </a>
          </div>

          <div class="field">
            <a
              @click="signin"
              :class="['button is-info', { 'is-loading': disabled }]"
              :disabled="disabled"
            >
              <span class="icon">
                <i class="fas fa-sign-in-alt"></i>
              </span>
              <span>{{ $t('signin') }}</span>
            </a>

            <a
              @click="$root.showModalSignup = true"
              class="button is-danger is-pulled-right"
            >
              <span>{{ $t('signup') }}</span>
            </a>
          </div>
        </form>
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
      email: 'demo@piemeram.lv',
      password: 'demons'
    }),
    methods: {
      signin () {
        this.disabled = true
        this.errors = {}

        axios
          .post('signin', {
            email: this.email,
            password: this.password
          })
          .then(response => {
            let token = response.data
            this.$root.auth.token = token
            axios.defaults.headers.common['Authorization'] = token
            localStorage.setItem('access_token', token)

            axios
              .get('auth')
              .then(response => {
                this.disabled = false

                let user = response.data
                this.$root.auth.user = user
                localStorage.setItem('user', JSON.stringify(user))

                this.$emit('close')
              })
              .catch(this.handleError)
          })
          .catch(this.handleError)
      }
    }
  }
</script>

<i18n>
  {
    "en": {
      "signin": "Sing in",
      "rememberme": "Remember me",
      "signup": "Sing up",
      "email": "E-mail",
      "password": "Password",
      "forgotpassword": "Forgot the password?",
      "invalid_credentials": "These credentials do not match our records."
    },
    "lv": {
      "signin": "Ienākt",
      "rememberme": "Atcerēties mani",
      "signup": "Pierakstīties",
      "email": "E-pasts",
      "password": "Parole",
      "forgotpassword": "Aizmirsi paroli?",
      "invalid_credentials": "Šie akreditācijas dati neatbilst mūsu reģistriem"
    }
  }
</i18n>
