<template>
  <nav class="navbar is-dark">
    <div class="navbar-brand">
      <a class="navbar-item">
        <router-link :to="{ name: 'home' }">Blog</router-link>
      </a>

      <a class="navbar-burger burger">
        <span></span>
        <span></span>
        <span></span>
      </a>
    </div>

    <div class="navbar-menu">
      <div class="navbar-start">
        <a class="navbar-item">
          <router-link :to="{ name: 'start' }">Start</router-link>
        </a>
      </div>

      <div class="navbar-end">
        <a class="navbar-item">
          <router-link :to="{ name: 'end' }">End</router-link>
        </a>
      </div>
    </div>
  </nav>
</template>
