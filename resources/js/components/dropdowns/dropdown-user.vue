<template>
  <div
    @click="dropdownIsActive = !dropdownIsActive"
    :class="['has-dropdown', { 'is-active': dropdownIsActive }]"
    ref="dropdown"
  >
    <div class="navbar-link">
      {{ $root.user.name }}
    </div>

    <div :class="['navbar-dropdown', { 'is-active': dropdownIsActive }]">
      <router-link
        :to="{ name: 'profile' }"
        @click.native="$emit('closeNavbar')"
        class="navbar-item is-active-hidden"
      >
        <span class="icon">
          <i class="fas fa-user"></i>
        </span>
        <span>{{ $t('profile') }}</span>
      </router-link>
    </div>
  </div>
</template>

<script>
  export default {
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
      "profile": "Profile",
      "verify": "Verify your e-mail address"
    },
    "lv": {
      "profile": "Profils",
      "verify": "Aplieciniet savu e-pasta adresi"
    }
  }
</i18n>
