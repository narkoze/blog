<template>
  <nav class="navbar is-dark">
    <div class="navbar-brand">
      <router-link
        :to="{ name: 'home' }"
        class="navbar-item"
      >
        Blog
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
        <router-link
          :to="{ name: 'end' }"
          class="navbar-item"
        >
          End
        </router-link>
      </div>
    </div>
  </nav>
</template>
