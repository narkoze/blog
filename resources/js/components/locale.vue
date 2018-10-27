<template>
  <div class="navbar-item has-dropdown is-hoverable">
    <a
      v-if="$route.params.lang === 'lv'"
      class="navbar-link"
    >
      <span class="flag-icon flag-icon-lv"></span>
      LV
    </a>
    <a
      v-else
      class="navbar-link"
    >
      <span class="flag-icon flag-icon-gb"></span>
      EN
    </a>

    <div class="navbar-dropdown">
      <a
        v-if="$route.params.lang === 'en'"
        @click="setLang('lv')"
        class="navbar-item"
      >
        <span class="flag-icon flag-icon-lv"></span>
        LV
      </a>
      <a
        v-else
        @click="setLang('en')"
        class="navbar-item"
      >
        <span class="flag-icon flag-icon-gb"></span>
        EN
      </a>
    </div>
  </div>
</template>

<script>
  export default {
    methods: {
      setLang (lang) {
        this.$router.replace({
          params: {
            ...this.$route.params,
            lang: lang
          }
        })
      }
    }
  }
</script>
