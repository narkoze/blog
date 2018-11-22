<template>
  <div class="sk-fading-circle">
    <div :class="['sk-circle1 sk-circle', color ]"></div>
    <div :class="['sk-circle2 sk-circle', color ]"></div>
    <div :class="['sk-circle3 sk-circle', color ]"></div>
    <div :class="['sk-circle4 sk-circle', color ]"></div>
    <div :class="['sk-circle5 sk-circle', color ]"></div>
    <div :class="['sk-circle6 sk-circle', color ]"></div>
    <div :class="['sk-circle7 sk-circle', color ]"></div>
    <div :class="['sk-circle8 sk-circle', color ]"></div>
    <div :class="['sk-circle9 sk-circle', color ]"></div>
    <div :class="['sk-circle10 sk-circle', color ]"></div>
    <div :class="['sk-circle11 sk-circle', color ]"></div>
    <div :class="['sk-circle12 sk-circle', color ]"></div>
  </div>
</template>

<script>
  export default {
    props: {
      color: {
        type: String,
        default: ''
      }
    }
  }
</script>


