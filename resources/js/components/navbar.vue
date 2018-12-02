<template>
  <nav class="navbar is-fixed-top is-dark">
    <div class="navbar-brand">
      <a
        href="https://github.com/narkoze/blog"
        target="_blank"
        class="navbar-item"
      >
        <i class="fas fa-code-branch"></i>
      </a>

      <router-link
        @click.native="navbarIsActive = false"
        :to="{ name: 'home' }"
        class="navbar-item has-text-weight-bold is-active-hidden"
      >
        {{ $t('title') }}
      </router-link>

      <router-link
        @click.native="navbarIsActive = false"
        :to="{
          name: 'posts',
          query: {
            page: 1
          }
        }"
        class="navbar-item"
      >
        {{ $t('posts') }}
      </router-link>

      <div
        @click="navbarIsActive = !navbarIsActive"
        :class="['navbar-burger burger', { 'is-active': navbarIsActive }]"
      >
        <span></span><span></span><span></span>
      </div>
    </div>

    <div :class="['navbar-menu', { 'is-active': navbarIsActive }]">
      <div class="navbar-end">
        <dropdown-user
          v-if="$root.user"
          class="navbar-item"
          @closeNavbar="navbarIsActive = false"
        >
        </dropdown-user>

        <div class="navbar-item">
          <p>
            <button-signout v-if="$root.user"></button-signout>
            <button-signin v-else></button-signin>

            <router-link
              v-if="$root.user && $root.user.role.id !== 2"
              @click.native="navbarIsActive = false"
              :to="{ name: 'admin' }"
              class="button is-link"
            >
              <span class="icon">
                <i class="fas fa-cogs"></i>
              </span>
              <span>{{ $t('admin') }}</span>
            </router-link>
          </p>
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
      "posts": "All posts",
      "admin": "Administrator",
      "posts": "All posts"
    },
    "lv": {
      "title": "Emuārs",
      "posts": "Visas ziņas",
      "admin": "Administrators",
      "posts": "Visas ziņas"
    }
  }
</i18n>

