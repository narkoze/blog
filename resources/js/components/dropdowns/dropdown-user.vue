<template>
  <div
    @click="getAuth"
    :class="['has-dropdown', { 'is-active': dropdownIsActive }]"
    ref="dropdown"
  >
    <div class="navbar-link has-text-primary">
      {{ $root.auth.user.name }}
    </div>

    <div class="navbar-dropdown has-text-centered">
      <a
        v-if="!$root.auth.user.email_verified_at"
        @click="$root.showModalEmailresend = true"
        class="navbar-item"
      >
        <span class="icon">
          <i class="fas fa-exclamation-triangle fa-xs has-text-warning"></i>
        </span>
        <span>{{ $t('verify') }}</span>
      </a>

      <i
        v-if="disabled"
        class="navbar-item fa fa-spinner fa-pulse"
      >
      </i>
    </div>
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
      dropdownIsActive: false
    }),
    created () {
      window.addEventListener('click', this.closeDropdown)
    },
    methods: {
      closeDropdown (e) {
        if (!this.$refs.dropdown.contains(e.target)) {
          this.dropdownIsActive = false
        }
      },
      getAuth () {
        this.dropdownIsActive = !this.dropdownIsActive
        if (!this.dropdownIsActive) return

        this.disabled = true

        axios
          .get('auth')
          .then(response => {
            this.disabled = false

            let user = response.data
            this.$root.auth.user = user
            localStorage.setItem('user', JSON.stringify(user))
          })
          .catch(this.handleError)
      }
    },
    beforeDestroy () {
      window.removeEventListener('click', this.closeDropdown)
    }
  }
</script>

<i18n>
  {
    "en": {
      "verify": "Verify your e-mail address"
    },
    "lv": {
      "verify": "Aplieciniet savu e-pasta adresi"
    }
  }
</i18n>
