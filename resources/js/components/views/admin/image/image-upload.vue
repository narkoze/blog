<template>
  <div>
    <transition
      name="fade"
      appear
    >
      <div class="card">
        <div class="card-content">
          <shared-image-upload></shared-image-upload>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
  import SharedImageUpload from './shared/shared-image-upload.vue'

  export default {
    components: {
      SharedImageUpload
    }
  }
</script>
