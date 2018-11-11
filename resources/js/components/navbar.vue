<template>
  <nav class="navbar is-fixed-top is-dark">
    <div class="navbar-brand">
      <router-link
        @click.native="navbarIsActive = false"
        :to="{ name: 'home' }"
        class="navbar-item"
      >
        {{ $t('title') }}
      </router-link>

      <div
        @click="navbarIsActive = !navbarIsActive"
        :class="['navbar-burger burger', { 'is-active': navbarIsActive }]"
      >
        <span></span><span></span><span></span>
      </div>
    </div>

    <div :class="['navbar-menu', { 'is-active': navbarIsActive }]">
      <div class="navbar-start">
      </div>

      <div class="navbar-end">
        <dropdown-user
          v-if="$root.user"
          class="navbar-item"
          @closeNavbar="navbarIsActive = false"
        >
        </dropdown-user>
        <div class="navbar-item">
          <button-signout v-if="$root.user"></button-signout>
          <button-signin v-else></button-signin>
        </div>
        <dropdown-locale
          class="navbar-item"
          @closeNavbar="navbarIsActive = false"
        >
        </dropdown-locale>
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
    },
    data: () => ({
      navbarIsActive: false
    })
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

