<template>
  <div class="modal is-active">
    <div
      @click="$emit('close')"
      class="modal-background"
    >
    </div>

    <div class="modal-content">
      <div class="box">
        <h1 class="title">{{ $t('signup') }}</h1>

        <form>
          <div class="field">
            <label :class="['label', { 'has-text-danger': errors.name }]">
              {{ $t('name') }}
              <p class="control has-icons-left">
                <input
                  v-model="name"
                  name="name"
                  type="text"
                  :class="['input', { 'is-danger': errors.name }]"
                  :disabled="disabled"
                >
                <span class="icon is-small is-left">
                  <i class="fas fa-user"></i>
                </span>
              </p>
            </label>
            <p v-if="errors.name" class="help is-danger">{{ errors.name.join() }}</p>
          </div>

          <div class="field">
            <label :class="['label', { 'has-text-danger': errors.email }]">
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
            <p v-if="errors.email" class="help is-danger">{{ errors.email.join() }}</p>
          </div>

          <div class="field">
            <label :class="['label', { 'has-text-danger': errors.password }]">
              {{ $t('password') }}
              <p class="control has-icons-left">
                <input
                  v-model="password"
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
            <label :class="['label', { 'has-text-danger': errors.password_confirmation }]">
              {{ $t('password_confirmation') }}
              <p class="control has-icons-left">
                <input
                  v-model="password_confirmation"
                  @keyup.enter="signup"
                  name="password_confirmation"
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
            @click="signup"
            :class="['button is-danger', { 'is-loading': disabled }]"
            :disabled="disabled"
          >
            <span>{{ $t('signup') }}</span>
          </a>
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

  export default {
    mixins: [
      ErrorHandler,
    ],
    data: () => ({
      name: null,
      email: null,
      password: null,
      password_confirmation: null,
      scrollY: null
    }),
    created () {
      this.scrollY = window.scrollY
      window.addEventListener('scroll', this.lockScroll)
    },
    methods: {
      signup () {
        this.disabled = true
        this.errors = {}

        this.$axios
          .post('signup', {
            name: this.name,
            email: this.email,
            password: this.password,
            password_confirmation: this.password_confirmation
          })
          .then(response => {
            this.$emit('close')

            this.$root.notify('success', this.$t('signup.success'))
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
      "signup": "Sign up",
      "name": "Name",
      "email": "E-mail",
      "password": "Password",
      "password_confirmation": "Password again",
      "signup.success": "You have successfully signed up and a confirmation link has been sent to your e-mail address"
    },
    "lv": {
      "signup": "Reģistrēties",
      "name": "Vārds",
      "email": "E-pasts",
      "password": "Parole",
      "password_confirmation": "Parole vēlreiz",
      "signup.success": "Jūs esat veiksmīgi reģistrējies un uz jūsu e-pasta adresi tika nosūtīta apstiprinājuma saite"
    }
  }
</i18n>
