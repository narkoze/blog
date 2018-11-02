<template>
  <nav class="navbar is-dark">
    <div class="navbar-brand">
      <router-link
        :to="{ name: 'home' }"
        class="navbar-item"
      >
        {{ $t('title') }}
      </router-link>

      <a class="navbar-burger burger">
        <span></span>
        <span></span>
        <span></span>
      </a>
    </div>

    <div class="navbar-menu">
      <div class="navbar-start">
      </div>

      <div class="navbar-end">
        <dropdown-user
          v-if="$root.auth.user"
          class="navbar-item"
        >
        </dropdown-user>
        <div class="navbar-item">
          <button-signout v-if="$root.auth.token"></button-signout>
          <button-signin v-else></button-signin>
        </div>
        <dropdown-locale class="navbar-item"></dropdown-locale>
      </div>
    </div>
  </nav>
</template>

<script>
  import DropdownUser from './dropdowns/dropdown-user.vue'
  import ButtonSignout from './buttons/button-signout.vue'
  import ButtonSignin from './buttons/button-signin.vue'
  import DropdownLocale from './dropdowns/dropdown-locale.vue'

  export default {
    components: {
      DropdownUser,
      ButtonSignout,
      ButtonSignin,
      DropdownLocale
    }
  }
</script>

<i18n>
  {
    "en": {
      "title": "Blog",
      "posts": "All posts"
    },
    "lv": {
      "title": "Emuārs",
      "posts": "Visas ziņas"
    }
  }
</i18n>

