<template>
  <nav class="navbar is-dark">
    <div class="navbar-brand">
      <router-link
        :to="{ name: 'home' }"
        class="navbar-item"
      >
        {{ $t('navbar.title') }}
      </router-link>

      <a class="navbar-burger burger">
        <span></span>
        <span></span>
        <span></span>
      </a>
    </div>

    <div class="navbar-menu">
      <div class="navbar-start">
        <router-link
          :to="{ name: 'start' }"
          class="navbar-item"
        >
          Start
        </router-link>
      </div>

      <div class="navbar-end">
        <locale></locale>
      </div>
    </div>
  </nav>
</template>

<script>
  import Locale from './locale.vue'

  export default {
    components: {
      Locale
    }
  }
</script>

