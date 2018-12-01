<template>
  <div class="modal is-active">
    <div
      @click="$emit('close')"
      class="modal-background"
    >
    </div>

    <div class="modal-content is-fullscreen">
      <div class="box">
        <shared-image-upload
          @uploaded="$emit('uploaded')"
        >
        </shared-image-upload>
      </div>
    </div>

    <button
      @click="$emit('close')"
      class="modal-close is-large"
    >
    </button>
  </div>
</template>

<script>
  import SharedImageUpload from '../views/admin/image/shared/shared-image-upload.vue'

  export default {
    components: {
      SharedImageUpload
    }
  }
</script>
