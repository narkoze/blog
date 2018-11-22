<template>
  <div
    @click="dropdownIsActive = !dropdownIsActive"
    :class="['has-dropdown', { 'is-active': dropdownIsActive }]"
    ref="dropdown"
  >
    <div class="navbar-link">
      <div v-if="$i18n.locale === 'lv'">
        <span class="flag-icon flag-icon-lv"></span>
        LV
      </div>
      <div v-else>
        <span class="flag-icon flag-icon-gb"></span>
        EN
      </div>
    </div>

    <div class="navbar-dropdown">
      <a
        v-if="$i18n.locale === 'en'"
        @click="handleLocale('lv')"
        class="navbar-item"
      >
        <span class="flag-icon flag-icon-lv"></span>
        LV
      </a>
      <a
        v-else
        @click="handleLocale('en')"
        class="navbar-item"
      >
        <span class="flag-icon flag-icon-gb"></span>
        EN
      </a>
    </div>
  </div>
</template>

<script>
  import LocaleHandler from '../../mixins/locale-handler'

  export default {
    mixins: [
      LocaleHandler,
    ],
    data: () => ({
      dropdownIsActive: false
    }),
    created () {
      window.addEventListener('click', this.closeDropdown)
    },
    methods: {
      ...this.handleLocale,
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
