<template>
  <a
    @click="signout"
    :class="['button is-info', { 'is-loading': disabled }]"
    :disabled="disabled"
  >
    <span class="icon">
      <i class="fas fa-sign-in-alt"></i>
    </span>
    <span>{{ $t('signout') }}</span>
  </a>
</template>

<script>
  import ErrorHandler from '../../mixins/error-handler'
  import axios from 'axios'

  export default {
    mixins: [
      ErrorHandler,
    ],
    methods: {
      signout () {
        this.disabled = true

        axios
          .get('signout')
          .then(response => {
            this.disabled = false

            delete axios.defaults.headers.common.Authorization
            localStorage.removeItem('token')

            this.$root.user = null
            localStorage.removeItem('user')

            this.$router.push({ name: 'home' })
          })
          .catch(this.handleError)
      }
    }
  }
</script>

<i18n>
  {
    "en": {
      "signout": "Sign out"
    },
    "lv": {
      "signout": "Iziet"
    }
  }
</i18n>
