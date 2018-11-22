<template>
  <div>
    <navbar></navbar>

    <div class="container-admin">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
  import Navbar from './navbar.vue'

  export default {
    components: {
      Navbar
    }
  }
</script>

